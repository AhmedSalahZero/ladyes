// $('body').on('click',function(){
// 	guestBtn.classList.remove("open");
// 	guestOptions.classList.remove("open");
// });
$(document).mouseup(function(e)
{
    var container = $(".booking-form__input");

    // if the target of the click isn't the container nor a descendant of the container
    if (!container.is(e.target) && container.has(e.target).length === 0)
    {
		guestBtn.classList.remove("open");
		guestOptions.classList.remove("open");
    }
});

const guestBtn = document.querySelector("#guests-input-btn"),
	guestOptions = document.querySelector("#guests-input-options"),
	adultsSubsBtn = document.querySelector("#adults-subs-btn"),
	adultsAddBtn = document.querySelector("#adults-add-btn"),
	childrenSubsBtn = document.querySelector("#children-subs-btn"),
	childrenAddBtn = document.querySelector("#children-add-btn"),
	adultsCountEl = document.querySelector("#guests-count-adults"),
	childrenCountEl = document.querySelector("#guests-count-children");
let maxNumGuests = 15,
	isGuestInputOpen = false,
	adultsCount = 1,
	childrenCount = 0;
updateValues();
guestBtn.addEventListener('click', function (e) {
	if (isGuestInputOpen) {
		guestBtn.classList.remove("open");
		guestOptions.classList.remove("open");
	} else {
		guestBtn.classList.add("open");
		guestOptions.classList.add("open");
	}
	isGuestInputOpen = isGuestInputOpen ? false : true;
	e.preventDefault();
});
adultsAddBtn.addEventListener('click', function () {
	adultsCount = addValues(adultsCount);
	updateValues();
});
adultsSubsBtn.addEventListener('click', function () {
	adultsCount = substractValues(adultsCount, 1);
	updateValues();
});
childrenAddBtn.addEventListener('click', function () {
	childrenCount = addValues(childrenCount);
    // rep();
    console.log('add new child');
	updateValues();
});

childrenSubsBtn.addEventListener('click', function () {
    console.log(childrenCount-1)
   console.log( document.querySelector('.child_list').children[childrenCount-1].remove());
	childrenCount = substractValues(childrenCount, 0);
	updateValues();
});

function calcTotalGuests() {
	return adultsCount + childrenCount;
}

function addValues(count) {
	return (calcTotalGuests() < maxNumGuests) ? count + 1 : count;
}

function substractValues(count, min) {
	return (count > min) ? count - 1 : count;
}

function updateValues() {
	let btnText = `${adultsCount} Adults`;
	btnText += (childrenCount > 0) ? `, ${childrenCount} Children` : '';
	guestBtn.innerHTML = btnText;
	adultsCountEl.innerHTML = adultsCount;
	childrenCountEl.innerHTML = childrenCount;
    $('.num_adults').val(adultsCount);
    $('.num_children').val(childrenCount);
	if (adultsCount == 1) {
		adultsSubsBtn.classList.add("disabled");
	} else {
		adultsSubsBtn.classList.remove("disabled");
	} if (childrenCount == 0) {
        $('.guests-input__options').addClass('sm_height');
        $('.guests-input__options').removeClass('lg_height');
         $('.child_list').fadeOut();
		childrenSubsBtn.classList.add("disabled");
	} else {
        $('.guests-input__options').removeClass('sm_height');
        $('.guests-input__options').addClass('lg_height');
         $('.child_list').fadeIn();
		childrenSubsBtn.classList.remove("disabled");
	} if (calcTotalGuests() == maxNumGuests) {
		adultsAddBtn.classList.add("disabled");
		childrenAddBtn.classList.add("disabled");
	} else {
		adultsAddBtn.classList.remove("disabled");
		childrenAddBtn.classList.remove("disabled");
	}
}


// data reaperter child age
function rep(){
    $('.repeater').repeater({
        initEmpty: true,
        show: function () {
            $(this).slideDown();
        },
        hide: function (deleteElement) {
            // if(confirm('Are you sure you want to delete this element?')) {
            //     $(this).slideUp(deleteElement);
            // }
            $(this).slideUp(deleteElement);
        },
        isFirstItemUndeletable: false
    })
}
rep();
