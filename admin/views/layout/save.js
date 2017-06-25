var OElementId = 0;
var StartOrderingCount = 0;

function StartOrdering(id) {
	if(StartOrderingCount < 2) {
		if(document.getElementById("oim" + id).classList.contains("active") == true) {
			document.getElementById("oim" + id).classList.remove("active");
			OElementId -= OElementId;
			StartOrderingCount--;
		} else {
			document.getElementById("oim" + id).classList.add("active");
			if(OElementId == 0) {
				OElementId += id;
			}
			StartOrderingCount++;
		}
	}
	
	if(StartOrderingCount == 2) {
		var oParent = document.getElementById("oim" + OElementId);
		var nParent = document.getElementById("oim" + id);
		nParent.parentNode.insertBefore(oParent, nParent.nextSibling);
		oParent.classList.remove("active");
		nParent.classList.remove("active");
		StartOrderingCount -= StartOrderingCount;
		OElementId -= OElementId;
	}
}
