<?php
/*
 * @package    Qatdatabase
 * @copyright  Copyright (C) 2015 - 2017 cprojects.org. All rights reserved.
 * @license    GNU General Public License version 3 or later; see LICENSE.txt
 */

// No direct access to this file
defined('_JEXEC') or die ('Restricted access');

jimport('joomla.application.component.modeladmin');

class QatDatabaseModelItem extends JModelAdmin {
	protected $Settings;
	
	public function __construct() {
		// Load component settings.
		$this->Settings = JComponentHelper::getParams('com_qatdatabase');
		
		return parent::__construct();
	}
	
	public function getTable($type = 'Item', $prefix = 'QatdatabaseTable', $config = array()) {
		return JTable::getInstance($type, $prefix, $config);
	}
	
	public function getForm($data = array(), $loadData = true) {
		
	}
	
	public function getItem($pk = null) {
		if($item = parent::getItem($pk)) {
			// Override itemdata column.
			$item->itemdata = json_decode($item->itemdata);
		}
		
		return $item;
	}
	
	public function postSaveHook($model, $data) {
		$item = $this->getItem();
		return $item->get('id');
	}
	
	/*
	 * File field data loader.
	 * 
	 * @param   string  $field    The field name.
	 * @param   string  $request  'extension' to load the allowed extension.
	 * 
	 * @return  The requested column by 'request' parameter, boolean false if any of parameters is null.
	 */
	protected function getFilefield($field = null, $request = null) {
		if($field == null) {
			return false;
		}
		
		$db = JFactory::getDBO();
		
		$query = $db->getQuery(true);
		$query->select('*')->from($db->quoteName('#__qatdatabase_fields'))->where('name=\'' . $field . '\'');
		
		$db->setQuery($query);
		$fieldCol = $db->loadObjectList();
		
		switch($request) {
			case null:
				return false;
				break;
			
			case 'extension':
				return $fieldCol[0]->parameters;
				break;
				
			case 'maxfiles':
				return $fieldCol[0]->parameters_2;
				break;
			
			case 'maxsize':
				if($fieldCol[0]->max_file_size !== '' || $fieldCol[0]->max_file_size == 0) {
					return $fieldCol[0]->max_file_size;
				} else {
					return 100;
				}
				break;
		}
		
		return false;
	}
	
	/*
	 * Fields validation function.
	 * 
	 * @param   array   $data       Submitted data array.
	 * @param   string  $fieldname  field name to check if is in the selected category/categories.
	 * 
	 * @return  boolean, true if all data is ok, false if the check failed, if field name not null: if field inside the selected category/categories returns true, if not returns false.
	 */
	protected function ValidateData($data, $fieldname = null) {
		// First check token.
		JSession::checkToken() or die('Invalid Token');
		
		if(is_array($data['catid'])) {
			$data['catid'] = implode(',', $data['catid']);
		}
		
		// Load db.
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('*')->from('#__qatdatabase_fields')->where('published=\'1\' AND catid IN (-1,' . $data['catid'] . ')');
		$db->setQuery($query);
		$Fields = $db->loadObjectList();
		
		if($fieldname == null) {
			// Set no errors.
			$error = 0;
			
			// Check each field.
			foreach($Fields as $field) {
				// If file or image field.
				if($field->required == 1 && ($field->type == 13 || $field->type == 14) && (!isset($data[$field->name]) || (isset($data[$field->name]) && $data[$field->name] == ''))) {
					if(isset($_FILES[$field->name . '_file'])) {
						if($field->type == 13 && $_FILES[$field->name . '_file']['name'] == '') {
							$error = 1;
						} elseif($field->type == 14 && $_FILES[$field->name . '_image']['name'] == '') {
							$error = 1;
						}
					} else {
						$error = 1;
					}
				} elseif($field->required == 1 && (!isset($data[$field->name]) || $data[$field->name] == '')) {
					$error = 1;
				}
			}
			
			if($error == 1) {
				return false;
			}
			
			return true;
		} else {
			$Count = 0;
			
			foreach($Fields as $field) {
				if($field->name == $fieldname) {
					$Count++;
					break;
				}
			}
			
			if($Count == 1) {
				return true;
			}
			
			return false;
		}
	}
	
	public function save($data) {
		// Check data.
		if($data == null || $data == '') {
			return false;
		}
		
		// The fields which is not used (Like token) or has a column in `qatdatabase_items` table and is not needed to be inside the itemdata column.
		$exceptedFields = array('task', JSession::getFormToken(), 'id', 'alias', 'created_by', 'created', 'publish_up', 'publish_down', 'published', 'catid');
		
		// Assign each value to a key.
		foreach($exceptedFields as $exceptedField) {
			$exceptedFields[$exceptedField] = '';
		}
		
		// Assign created by, creation date and publish up date when adding a new item.
		if($data['id'] == '0') {
			$data['created_by'] = JFactory::getUser()->id;
			$data['created'] = JFactory::getDate()->toSql();
			$data['publish_up'] = JFactory::getDate()->toSql();
		} else {
			$data['created'] = $this->getItem()->created;
			$data['publish_up'] = $this->getItem()->publish_up;
		}
		
		// Set the unassigned data variable.
		$unassignedData = array();
		
		// Check the unassigned post data.
		foreach($data as $field => $fieldvalue) {
			if(!isset($exceptedFields[$field]) && $this->ValidateData($data, $field) == true) {
				if(isset($unassignedData[$field]) && is_array($unassignedData[$field])) {
					$unassignedData[$field] = array($fieldvalue);
				} else {
					$unassignedData[$field] = $fieldvalue;
				}
			}
		}
		
		// Check if all categories selected.
		if(is_array($data['catid']) && in_array('-1', $data['catid'])) {
			$data['catid'] = '-1';
		} else {
			if(is_array($data['catid'])) {
				$data['catid'] = implode(',', $data['catid']);
			}
		}
		
		// Validate data, continue if true, break if false.
		if($this->ValidateData($data) == false) {
			return false;
		}
		
		// Files uploading.
		jimport('joomla.filesystem.file');
		jimport('joomla.filesystem.folder');
		
		foreach($_FILES as $Key => $File) {
			if(implode(',', $File['name']) !== '' || (!is_array($File['name']) && $File['name'] !== '')) {
				$Filename = $File['name'];
				$Source = $File['tmp_name'];
				$FileField = substr($Key, 0, -5);
				$ImageField = substr($Key, 0, -6);
				
				// If file field.
				if(substr($Key, -5) == '_file' && $this->ValidateData($data, substr($Key, 0, -5)) == true) {
					// Get field name.
					$Field = substr($Key, 0, -5);
					$UpDir = 'files';
				} elseif(substr($Key, -6) == '_image' && $this->ValidateData($data, substr($Key, 0, -6)) == true) {
					// Get field name.
					$Field = substr($Key, 0, -6);
					$UpDir = 'images';
				}
				
				// Get max number of files.
				$MaxFiles = $this->getFilefield($Field, 'maxfiles');
				
				// Get max file size (Converted to byte).
				$MaxFileSize = $this->getFilefield($Field, 'maxsize') * 1000;
				
				// Destenation after upload: uploads/files|images/FIELD_NAME/
				$DestenationDir = JPATH_ROOT . DS . 'media' . DS . 'com_qatdatabase' . DS . 'uploads' . DS . $UpDir . DS . $Field;
				
				// New file name: FIELD-NAME_uniqid(TIME)
				$newFilename = $Field . '_' . uniqid(time()) . '.' . JFile::getExt($Filename);
				
				$Destenation = $DestenationDir . DS . $newFilename;
				
				// Check if the destination directory exist.
				if(!JFolder::exists($DestenationDir)) {
					if(!JFolder::create($DestenationDir)) {
						return false;
					}
				}
				
				// If adding new item.
				if((int) $this->getItem()->id == 0) {
					$upload = true;
				} else {
					// If editing an item, check if the item has an uploaded file already.
					if(isset($this->getItem()->itemdata->$Field) && $this->getItem()->itemdata->$Field !== '') {
						$newFilename = $this->getItem()->itemdata->$Field;
						$Destenation = $DestenationDir . DS . $newFilename;
						
						if($UpDir == 'files') {
							if(JFile::exists($Destenation)) {
								if(JFile::delete($Destenation)) {
									$upload = true;
								} else {
									$upload = false;
								}
							} else {
								$upload = true;
							}
						} elseif($UpDir == 'images') {
							$ImagesCount = 0;
							$FilesList = '';
							
							foreach(explode(',', $this->getItem()->itemdata->$Field) as $ImagePath) {
								$Path = $DestenationDir . DS . $ImagePath;
								
								if(JFile::exists($Path)) {
									$FilesList += $ImagePath . ',';
									$ImagesCount++;
								}
							}
							
							if($ImagesCount < $MaxFiles) {
								if($ImagesCount == 0) {
									$ImagesStartNum = 1;
								} else {
									$ImagesStartNum = $ImagesCount + 1;
								}
								
								$upload = true;
							} elseif($ImagesCount >= $MaxFiles) {
								$upload = false;
							}
						}
					} else {
						$upload = true;
					}
				}
				
				if($upload == true) {
					if($UpDir == 'files') {
						// Check if the extension matches the allowed extension and check size.
						if(strtolower(JFile::getExt($Filename)) == $this->getFilefield($Field, 'extension') && $File['size'] <= $MaxFileSize) {
							if(JFile::exists($Destenation)) {
								return false;
							} else {
								if(!JFile::upload($Source, $Destenation)) {
									return false;
								}
							}
						} else {
							return false;
						}
					} elseif($UpDir == 'images') {
						// Start files count.
						if(isset($ImagesStartNum)) {
							$FilesNumber = $ImagesStartNum;
						} else {
							$FilesNumber = 1;
						}
						
						if(isset($FilesList)) {
							if(substr($FilesList, -1) == ',') {
								$newFilename = substr($FilesList, 0, -1);
							} else {
								$newFilename = $FilesList;
							}
						} else {
							$newFilename = '';
						}
						
						foreach($Filename as $ImageKey => $ImageValue) {
							$ImageTmpName = $File['tmp_name'][$ImageKey];
							$ImageSize = $File['size'][$ImageKey];
							$newImagename = $Field . '_' . uniqid(time()) . '_' . $FilesNumber . '.' . JFile::getExt($ImageValue);
							$Destenation = $DestenationDir . DS . $newImagename;
							
							// Check size and extension for each image.
							if($ImageSize <= $MaxFileSize && in_array(strtolower(JFile::getExt($ImageValue)), explode(',', $this->getFilefield($Field, 'extension')))) {
								if($FilesNumber == $MaxFiles) {
									$newFilename .= $newImagename;
								} else {
									$newFilename .= $newImagename . ',';
								}
								
								JFile::upload($ImageTmpName, $Destenation);
							}
							
							// Break if reached max files number.
							if($FilesNumber == $MaxFiles) {
								break;
							} else {
								if($ImageSize <= $MaxFileSize && in_array(strtolower(JFile::getExt($ImageValue)), explode(',', $this->getFilefield($Field, 'extension')))) {
									$FilesNumber++;
								}
							}
						}
					}
				}
				
				// Assign file field name without '_file' (The last five letters) to unassignedData array.
				$unassignedData[$Field] = $newFilename;
			}
		}
		
		// Assign all unassigned data to the itemdata column.
		$data['itemdata'] = json_encode($unassignedData);
		
		if(parent::save($data)) {
			return true;
		}
		
		return false;
	}
	
	protected function RenderField($TypeId, $FieldTitle, $FieldName, $Placeholder, $Defaultvalue, $Names = null, $Values = null, $Rows = null, $Cols = null, $MaxFileSize = null, $Parameters = null, $Parameters2 = null, $MaxLength = null, $Required = '0') {
		if($this->getItem()->id == '0') {
			if($Defaultvalue == '') {
				$ItemFieldValue = null;
			} else {
				$ItemFieldValue = $Defaultvalue;
			}
		} else {
			if(isset($this->getItem()->itemdata->$FieldName)) {
				$ItemFieldValue = $this->getItem()->itemdata->$FieldName;
			} elseif($Defaultvalue !== '') {
				$ItemFieldValue = $Defaultvalue;
			}
		}
		
		switch($TypeId) {
			case 1:
				$count = '0';
				$value = explode(',', $Values);
				$names = explode(',', $Names);
				
				if(isset($ItemFieldValue)) {
					$isChecked = array();
					
					if(!is_array($ItemFieldValue)) {
						$ItemFieldValue = explode(',', $ItemFieldValue);
					}
					
					// Convert values to keys (default keys not needed).
					foreach($ItemFieldValue as $key => $val) {
						$isChecked[$val] = 'true';
					}
				}
				
				$field = '<fieldset' . (($Required == '1') ? ' ' . $this->required['input'] . ' ' : ' ') . 'id="' . $FieldName . '" class="qatdatabase-checkbox-group checkboxes ' . (($Required == '1') ? $this->required['class'] : '') . '">';
				foreach($names as $name) {
					if(isset($isChecked[$value[$count]])) {
						$checked = true;
					} else {
						$checked = false;
					}
					
					$field .= '<div id="' . $FieldName . '" class="qatdatabase-checkbox-item">';
					$field .= '<label class="qatdatabase-label" for="' . $FieldName . $count . '">' . $name . '</label>';
					$field .= '<input style="float: none; margin: 0px;"' . ((isset($checked) && $checked !== null && $checked == true) ? ' checked ' : ' ') . 'id="' . $FieldName . $count . '" type="checkbox" value="' . $value[$count] . '" name="' . $FieldName . '[]" />';
					$field .= '</div>';
					$count++;
				}
				$field .= '</fieldset>';
				
				$return = $field;
				break;
			
			case 2:
				$field = '<input ' . ((isset($ItemFieldValue) && $ItemFieldValue !== null && $ItemFieldValue == $FieldName) ? 'checked ' : '') . 'class="' . (($Required == '1') ? $this->required['class'] : '') . '" id="' . $FieldName . '" name="' . $FieldName . '" value="' . $FieldName . '" type="checkbox"' . (($Required == '1') ? ' ' . $this->required['input'] : '') . ' />';
				$return = $field;
				break;
			
			case 3:
				if($Parameters == '[jtype]') {
					$return = JHTML::_('calendar', ((isset($ItemFieldValue) && $ItemFieldValue !== null) ? $ItemFieldValue : ''), $FieldName, $FieldName, '%Y-%m-%d', array('class' => $this->required['class']));
				} elseif($Parameters == '[btype]') {
					$field = '<input value="' . ((isset($ItemFieldValue) && $ItemFieldValue !== null) ? $ItemFieldValue : '') . '" class="' . (($Required == '1') ? $this->required['class'] : '') . '" id="' . $FieldName . '" name="' . $FieldName . '" type="date"' . (($Required == '1') ? ' ' . $this->required['input'] : '') . ' />';
					$return = $field;
				}
				break;
			
			case 4:
				$count = '0';
				$value = explode(',', $Values);
				$names = explode(',', $Names);
				
				if(isset($ItemFieldValue)) {
					$isChecked = array();
					
					if(!is_array($ItemFieldValue)) {
						$ItemFieldValue = explode(',', $ItemFieldValue);
					}
					
					// Convert values to keys (default keys not needed).
					foreach($ItemFieldValue as $key => $val) {
						$isChecked[$val] = 'true';
					}
				}
				
				$field = '<div class="qatdatabase-select-group">';
				$field .= '<select id="' . $FieldName .  '" multiple="multiple"' . (($Required == '1') ? ' ' . $this->required['input'] : '') . ' name="' . $FieldName . '[]" class="qatdatabase-options-field' . (($Required == '1') ? ' ' . $this->required['class'] : '') . '">';
				foreach($names as $name) {
					if(isset($isChecked[$value[$count]])) {
						$checked = true;
					} else {
						$checked = false;
					}
					$field .= '<option ' . ((isset($checked) && $checked !== null && $checked == true) ? 'selected ' : '') . 'id="' . $FieldName . $count . '" value="' . $value[$count] . '">' . $name . '</option>';
					$count++;
				}
				$field .= '</select>';
				$field .= '</div>';
				
				$return = $field;
				break;
			
			case 5:
				$count = '0';
				$value = explode(',', $Values);
				$names = explode(',', $Names);
				$field = '<div class="qatdatabase-select-group">';
				$field .= '<select id="' . $FieldName . '" name="' . $FieldName . '"' . (($Required == '1') ? ' ' . $this->required['input'] : '') . ' class="qatdatabase-option-field' . (($Required == '1') ? ' ' . $this->required['class'] : '') . '">';
				foreach($names as $name) {
					$field .= '<option' . ((isset($ItemFieldValue) && $ItemFieldValue !== null && $ItemFieldValue == $value[$count]) ? ' selected ' : ' ') . ' id="' . $FieldName . $count . '" value="' . $value[$count] . '">' . $name . '</option>';
					$count++;
				}
				$field .= '</select>';
				$field .= '</div>';
				
				$return = $field;
				break;
			
			case 6:
				$field = '<input ' . (($Placeholder !== '') ? 'placeholder="' . $Placeholder . '" ' : '') . 'value="' . ((isset($ItemFieldValue) && $ItemFieldValue !== null) ? $ItemFieldValue : '') . '" class="' . (($Required == '1') ? $this->required['class'] : '') . '"' . (($Required == '1') ? ' ' . $this->required['input'] : '') . ' id="' . $FieldName . '" type="email" maxlength="' . $MaxLength . '" name="' . $FieldName . '" />';
				$return = $field;
				break;
			
			case 7:
				$field = '<input ' . (($Placeholder !== '') ? 'placeholder="' . $Placeholder . '" ' : '') . 'value="' . ((isset($ItemFieldValue) && $ItemFieldValue !== null) ? $ItemFieldValue : '') . '" class="' . (($Required == '1') ? $this->required['class'] : '') . '"' . (($Required == '1') ? ' ' . $this->required['input'] : '') . ' id="' . $FieldName . '" type="number" maxlength="' . $MaxLength . '" name="' . $FieldName . '" />';
				$return = $field;
				break;
			
			case 8:
				$field = '<input ' . (($Placeholder !== '') ? 'placeholder="' . $Placeholder . '" ' : '') . 'value="' . ((isset($ItemFieldValue) && $ItemFieldValue !== null) ? $ItemFieldValue : '') . '" class="' . (($Required == '1') ? $this->required['class'] : '') . '"' . (($Required == '1') ? ' ' . $this->required['input'] : '') . ' id="' . $FieldName . '" type="number" name="' . $FieldName . '" /> <span id="' . $FieldName . 'currency" class="' . $FieldName . ' currency">' . $Parameters . '</span>';
				$return = $field;
				break;
			
			case 9:
				if($Parameters == '[editor]') {
					$editor = JFactory::getEditor();
					$params = array('smilies' => '1', 'style' => '1', 'layer' => '0', 'table' => '0', 'clear_entities' => '0');
					$field = $editor->display($FieldName, ((isset($ItemFieldValue) && $ItemFieldValue !== null) ? $ItemFieldValue : ''), $Rows * 10, $Cols * 10, $Cols, $Rows, false, '', '', '', $params);
				}
				
				if($Parameters == '[textarea]') {
					$field = '<textarea ' . (($Placeholder !== '') ? 'placeholder="' . $Placeholder . '" ' : '') . 'class="' . (($Required == '1') ? $this->required['class'] : '') . '"' . (($Required == '1') ? ' ' . $this->required['input'] : '') . ' id="' . $FieldName . '" rows="' . $Rows . '" cols="' . $Cols . '" name="' . $FieldName . '">' . ((isset($ItemFieldValue) && $ItemFieldValue !== null) ? $ItemFieldValue : '') . '</textarea>';
				}
				
				$return = $field;
				break;
			
			case 10:
				$field = '<input ' . (($Placeholder !== '') ? 'placeholder="' . $Placeholder . '" ' : '') . 'value="' . ((isset($ItemFieldValue) && $ItemFieldValue !== null) ? $ItemFieldValue : '') . '" class="' . (($Required == '1') ? $this->required['class'] : '') . '"' . (($Required == '1') ? ' ' . $this->required['input'] : '') . ' id="' . $FieldName . '" type="text" name="' . $FieldName . '" maxlength="' . $MaxLength . '" />';
				$return = $field;
				break;
			
			case 11:
				$field = '<input ' . (($Placeholder !== '') ? 'placeholder="' . $Placeholder . '" ' : '') . 'value="' . ((isset($ItemFieldValue) && $ItemFieldValue !== null) ? $ItemFieldValue : '') . '" class="' . (($Required == '1') ? $this->required['class'] : '') . '"' . (($Required == '1') ? ' ' . $this->required['input'] : '') . ' id="' . $FieldName . '" type="url" name="' . $FieldName . '" maxlength="' . $MaxLength . '" />';
				$return = $field;
				break;
			
			case 12:
				$count = '0';
				$value = explode(',', $Values);
				$names = explode(',', $Names);
				$field = '<fieldset' . (($Required == '1') ? ' ' . $this->required['input'] . ' ' : ' ') . 'id="' . $FieldName . '" class="qatdatabase-radio-group radio ' . (($Required == '1') ? $this->required['class'] : '') . '">';
				foreach($names as $name) {
					$field .= '<label class="radio-item-label"><span>' . $name . '</span> ';
					$field .= '<input style="float: none; margin: 0px;"' . ((isset($ItemFieldValue) && $ItemFieldValue !== null && $ItemFieldValue == $value[$count]) ? ' checked ' : ' ') . 'type="radio" id="' . $FieldName . $count . '" name="' . $FieldName . '"' . (($Required == '1') ? ' ' . $this->required['input'] . ' ' : ' ') . 'value="' . $value[$count] . '" />';
					$field .= '</label>';
					$count++;
				}
				$field .= '</fieldset>';
				
				$return = $field;
				break;
			
			case 13:
				$field = '';
				
				$HasFile = false;
				
				if(isset($ItemFieldValue) && $ItemFieldValue !== '') {
					jimport('joomla.filesystem.file');
					
					$file = JPATH_ROOT . DS . 'media' . DS . 'com_qatdatabase' . DS . 'uploads' . DS . 'files' . DS . $FieldName . DS . $ItemFieldValue;
					
					if(JFile::exists($file)) {
						$File = JUri::base(true) . DS . 'media' . DS . 'com_qatdatabase' . DS . 'uploads' . DS . 'files' . DS . $FieldName . DS . $ItemFieldValue;
						$field .= '<a href="' . str_replace(DS . 'administrator', '', $File) . '" target="_blank">' . $ItemFieldValue . '</a><br />';
						$field .= '<input id="' . $FieldName . '" type="hidden" name="' . $FieldName . '" value="' . $ItemFieldValue . '" />';
						
						$HasFile = true;
					}
				}
				
				$field .= '<input id="' . $FieldName . '_file" class="' . (($Required == '1' && $this->getItem()->id == '0' || ($HasFile == false && $Required == '1')) ? $this->required['class'] : '') . '"' . (($Required == '1' && $this->getItem()->id == '0' || ($HasFile == false && $Required == '1')) ? ' ' . $this->required['input'] : '') . ' type="file" name="' . $FieldName . '_file" />';
				$field .= '<p class="' . $FieldName . '_file_notice"><b>' . JText::_('COM_QATDATABASE_FIELD_FILE_ALLOWED_EXTENSION') . '</b>: ' . $Parameters . '</p>';
				if($MaxFileSize !== null && $MaxFileSize > 0) {
					$field .= '<p class="' . $FieldName . '_file_notice"><b>' . JText::_('COM_QATDATABASE_FIELD_FILE_MAX_FILE_SIZE') . '</b>: ' . $MaxFileSize . ' ' . JText::_('COM_QATDATABASE_GENERAL_KB') . '</p>';
				} else {
					$field .= '<p class="' . $FieldName . '_file_notice"><b>' . JText::_('COM_QATDATABASE_FIELD_FILE_MAX_FILE_SIZE') . '</b>: 100 ' . JText::_('COM_QATDATABASE_GENERAL_KB') . '</p>';
				}
				$return = $field;
				break;
			
			case 14:
				jimport('joomla.filesystem.file');
				$InputValue = '';
				$Count = 0;
				$NoListClass = ' nolist';
				
				if(isset($ItemFieldValue) ) {
					foreach(explode(',', $ItemFieldValue) as $Image) {
						$file = JPATH_ROOT . DS . 'media' . DS . 'com_qatdatabase' . DS . 'uploads' . DS . 'images' . DS . $FieldName . DS . $Image;
						$Link = JUri::base(true) . DS . 'media' . DS . 'com_qatdatabase' . DS . 'uploads' . DS . 'images' . DS . $FieldName . DS . $Image;
						
						if(JFile::exists($file)) {
							$NoListClass = '';
							break;
						} else {
							$NoListClass = ' nolist';
						}
					}
				}
				
				$field = '<label for="' . $FieldName . '_image" id="' . $FieldName . '_image_label" class="btn btn-primary ' . $FieldName . '_image_label image_add">' . JText::_('COM_QATDATABASE_ITEM_ADD_IMAGES') . '</label>';
				$field .= '<input onchange="ImageInput(this.id, \'' . $FieldName . '_image_label\', ' . $Parameters2 . ')" id="' . $FieldName . '_image" ' . (($Parameters2 !== null && $Parameters2 > 1) ? ' multiple="multiple"' : '') . 'type="file" name="' . $FieldName . '_image' . (($Parameters2 !== null && $Parameters2 > 1) ? '[]' : '') . '" class="image_add_input' . (($Required == '1' && $this->getItem()->id == '0') ? ' ' . $this->required['class'] : '') . '" />';
				$field .= '<div id="' . $FieldName . '_image_list" class="' . $FieldName . '_image_list fld_images_list' . $NoListClass . '">';
				if((int) $this->getItem()->id !== 0) {
					if(isset($ItemFieldValue)) {
						foreach(explode(',', $ItemFieldValue) as $Image) {
							$file = JPATH_ROOT . DS . 'media' . DS . 'com_qatdatabase' . DS . 'uploads' . DS . 'images' . DS . $FieldName . DS . $Image;
							$Link = JUri::base(true) . DS . 'media' . DS . 'com_qatdatabase' . DS . 'uploads' . DS . 'images' . DS . $FieldName . DS . $Image;
							
							if(JFile::exists($file)) {
								if($Count == $this->getFilefield($FieldName, 'maxfiles')) {
									$InputValue .= $Image;
								} else {
									$InputValue .= $Image . ',';
								}
								
								$field .= '<a class="' . $FieldName . '_image_preview_link fld_image_preview_link" href="' . str_replace(DS . 'administrator', '', $Link) . '" target="_blank"><img class="' . $FieldName . '_image_preview fld_image_preview" src="' . str_replace(DS . 'administrator', '', $Link) . '" /></a>';
							}
							
							$Count++;
						}
						
						if($InputValue !== '') {
							$field .= '<input type="hidden" name="' . $FieldName . '" value="' . $InputValue . '" />';
						}
					}
				}
				$field .= '</div>';
				$field .= '<p class="' . $FieldName . '_image_notice"><b>' . JText::_('COM_QATDATABASE_FIELD_IMAGES_MAX_NUMBER') . '</b>: ' . $Parameters2 . '</p>';
				if($MaxFileSize !== null && $MaxFileSize > 0) {
					$field .= '<p class="' . $FieldName . '_image_notice"><b>' . JText::_('COM_QATDATABASE_FIELD_FILE_MAX_FILE_SIZE') . '</b>: ' . $MaxFileSize . ' ' . JText::_('COM_QATDATABASE_GENERAL_KB') . '</p>';
				} else {
					$field .= '<p class="' . $FieldName . '_image_notice"><b>' . JText::_('COM_QATDATABASE_FIELD_FILE_MAX_FILE_SIZE') . '</b>: 100 ' . JText::_('COM_QATDATABASE_GENERAL_KB') . '</p>';
				}
				$field .= '<p class="' . $FieldName . '_image_notice"><b>' . JText::_('COM_QATDATABASE_FIELD_FILE_ALLOWED_EXTENSIONS') . '</b>: ' . $Parameters . '</p>';
				$return = $field;
				break;
		}
		
		return $return;
	}
	
	protected function IsTableEmpty($Table) {
		$db = JFactory::getDBO();
		$query = "SELECT COUNT(*) FROM `" . $Table . "`";
		$db->setQuery($query);
		$Result = $db->loadResult();
		
		if($Result == 0) {
			return false;
		} else {
			return true;
		}
	}
	
	public function GetCategoriesNumber() {
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('id')->from('#__categories')->where('extension=\'com_qatdatabase\' AND published=\'1\'');
		$db->setQuery($query);
		return count($db->loadObjectList());
	}
	
	protected function LoadCategoryField($selected = 0) {
		$CatsNumber = $this->Settings->get('cats_number', 1);
		
		if($CatsNumber > 1 || $CatsNumber == -1 && $CatsNumber !== 0) {
			$multiple = 'multiple="multiple" max-cats="' . $CatsNumber . '" name="catid[]" onchange="categoryChange(jQuery(this).attr(\'id\'), true);" ';
			$title = ' title="' . JText::_('COM_QATDATABASE_FIELD_CATEGORY_MAX_CATEGORIES') . ': ' . $CatsNumber . '" ';
		} else {
			$multiple = 'name="catid" onchange="categoryChange(jQuery(this).attr(\'id\'));" ';
			$title = '';
		}
		
		$SelectedCats = explode(',', $selected);
		$isSelected = array();
		
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('*')->from('#__categories')->where('extension=\'com_qatdatabase\' AND published=\'1\'');
		$db->setQuery($query);
		$Categories = $db->loadObjectList();
		
		if($CatsNumber !== 1 && $CatsNumber == -1) {
			if(count($SelectedCats) == count($Categories)) {
				$selected = '-1';
			}
		} else {
			foreach($SelectedCats as $selectedCat => $value) {
				$isSelected[$value] = ' selected';
			}
		}
		
		$return = '<div class="control-group qdbcategory">';
		$return .= '<div class="control-label">';
		$return .= '<label ' . $title . ' class="qatdatabase-label hasTooltip" for="qdbcategory">';
		$return .= '<h4 class="qatdatabase-ilheading">' . JText::_('COM_QATDATABASE_FIELD_SELECT_CATEGORY_LABEL') . ': </h4>';
		$return .= '<span class="qatdatabase-required-star">*</span>';
		$return .= '</label>';
		$return .= '</div>';
		$return .= '<select ' . $multiple . 'id="qdbcategory" required="required" class="required">';
		
		if($CatsNumber == -1) {
			$return .= '<option ' . (($selected == '-1') ? 'selected="selected" ' : '') . 'value="-1">' . JText::_('COM_QATDATABASE_FIELD_ALL_CATEGORIES') . '</option>';
		} else {
			if($CatsNumber == 1 || $CatsNumber == 0) {
				$return .= '<option value="">' . JText::_('COM_QATDATABASE_FIELD_SELECT_CATEGORY') . '</option>';
			}
		}
		
		foreach($Categories as $Category) {
			if(isset($isSelected[$Category->id]) && $selected !== '-1') {
				$Selected = 'selected="selected" ';
			} else {
				$Selected = '';
			}
			
			$return .= '<option ' . $Selected . 'value="' . $Category->id . '">' . $Category->title . '</option>';
		}
		
		$return .= '</select>';
		$return .= '</div>';
		
		return $return;
	}
	
	public function loadPublishedField($Status = 1) {
		$isSelected = array();
		$isSelected[1] = '';
		$isSelected[0] = '';
		$isSelected[2] = '';
		$isSelected[-2] = '';
		$isSelected[$Status] = 'selected ';
		
		$return = '<label for="published">' . JText::_('JSTATUS') . ':</label>';
		$return .= '<select id="published" class="chzn-color-state" name="published">';
		$return .= '<option ' . $isSelected[1] . 'value="1">' . JText::_('JPUBLISHED') . '</option>';
		$return .= '<option ' . $isSelected[0] . 'value="0">' . JText::_('JUNPUBLISHED') . '</option>';
		$return .= '<option ' . $isSelected[2] . 'value="2">' . JText::_('JARCHIVED') . '</option>';
		$return .= '<option ' . $isSelected[-2] . 'value="-2">' . JText::_('JTRASHED') . '</option>';
		$return .= '</select>';
		
		return $return;
	}
	
	public function LoadFields() {
		if($this->getItem() !== '') {
			$CatId = $this->getItem()->catid;
		} else {
			$CatId = '';
		}
		
		$return = $this->LoadCategoryField($CatId);
		
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('*')->from('#__qatdatabase_fields AS field');
		
		if($this->IsTableEmpty('#__qatdatabase_fields_ordering') == 'false') {
			$query->join('RIGHT', '#__qatdatabase_fields_ordering AS fieldorder ON field.id = fieldorder.fieldid')->order('fieldorder.ordering ASC');
		}
		
		$query->where('field.published=1');
		
		$db->setQuery($query);
		$loadQuery = $db->loadObjectList();
		
		foreach($loadQuery as $field) {
			$this->required = array('text' => '*', 'class' => 'required', 'input' => 'required="required"');
			
			// If file input.
			if($field->type == '13') {
				$forLabel = ' for="' . $field->name . '_file"';
			} else {
				$forLabel = ' for="' . $field->name . '"';
			}
			
			$forClass = '';
			
			if($field->type == '2') {
				$inline = ' inline';
			} else {
				$inline = '';
			}
			
			if($field->description !== '') {
				$FieldDesc = '<span title="' . $field->description . '" class="hasTooltip qatdatabase-field-desc">!</span>';
			} else {
				$FieldDesc = '';
			}
			
			if(isset($field->labellink) && $field->labellink !== '' && filter_var($field->labellink, FILTER_VALIDATE_URL) == true) {
				$labellink = '<a href="' . $field->labellink . '" target="_blank">';
				$labellinkend = '</a>';
			} else {
				$labellink = '';
				$labellinkend = '';
			}
			
			// Get categories number.
			$Incats = count(explode(',', $field->catid));
			
			// Check if categories number equals all categories.
			if($this->GetCategoriesNumber() == $Incats) {
				$cats = '-1';
			} else {
				if($field->catid == '-1') {
					$cats = '-1';
				} else {
					$cats = $field->catid;
				}
			}
			
			if($cats == '-1' || $this->getItem()->catid == '-1') {
				$display = 'display: block;';
			} else {
				$displayCount = 0;
				
				// Check if any of the field's categories is selected.
				foreach(explode(',', $this->getItem()->catid) as $cat) {
					// Break the loop if any of the field's categories is selected.
					if($cat !== '' && strpos($cats, $cat) !== false) {
						$displayCount++;
						break;
					}
				}
				
				if($displayCount > 0) {
					$display = 'display: block;';
				} else {
					$display = 'display: none;';
				}
			}
			
			// Override the 'input' and 'class' from $this->required array if the input is hidden so it is not required.
			if($display == 'display: none;') {
				$this->required['class'] = 'req';
				$this->required['input'] = 'req="1"';
			}
			
			$return .= '<div style="' . $display . '" in-cats-array="' . $cats . '" class="field field-' . $field->name . ' categorydepends control-group' . $inline . '">';
			$return .= '<div class="control-label">';
			$return .= '<label class="qatdatabase-label' . $forClass . '"' . $forLabel . '><h4 class="qatdatabase-ilheading">' . $labellink . $field->title . $labellinkend . ': </h4>' . (($field->required == '1') ? '<span class="qatdatabase-required-star">' . $this->required['text'] . '</span> ' : '') . $FieldDesc . '</label>';
			$return .= '</div>';
			$return .= '<div class="controls qatdatabase-field' . $inline . '">';
			$return .= $this->RenderField($field->type, $field->title, $field->name, $field->placeholder, $field->default_value, $field->names, $field->values, $field->rows, $field->cols, $field->max_file_size, $field->parameters, $field->parameters_2, $field->max_length, $field->required);
			$return .= '</div>';
			$return .= '</div>';
		}
		
		return $return;
	}
}
?>