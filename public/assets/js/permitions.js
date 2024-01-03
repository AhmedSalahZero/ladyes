$(document).ready(function () {
    var checkboxes = document.querySelectorAll('input[class*="subOption_"]'),
        checkall = document.querySelectorAll('input[id*="option_"]')
    for(var i=0; i<checkboxes.length; i++) {
        checkboxes[i].onclick = function() {

            var current_class =this.classList[1].split("_")[1]
            // var checkedCount = document.querySelectorAll('input.subOption:checked').length;
            var  checkAll = document.getElementById('option_'+current_class);

            var checkedCount = document.querySelectorAll('input[class*="subOption_'+current_class+'"]:checked').length;
            console.log(checkedCount)
            console.log(checkAll)

            checkAll.checked = checkedCount > 0;
            var checkBoxes = document.querySelectorAll('input[class*="subOption_'+current_class+'"]')
            // $(document).ready(function () {
            checkAll.indeterminate = checkedCount > 0 && checkedCount < checkBoxes.length;
            // });
        }
    }
    for(var i=0; i<checkall.length; i++) {
        checkall[i].onclick = function() {
            var current_class =this.id.split("_")[1]
            var checkBoxes = document.querySelectorAll('input[class*="subOption_'+current_class+'"]')

            for(var i=0; i<checkBoxes.length; i++) {
                checkBoxes[i].checked = this.checked;
            }
        }
    }

});
