
function incrementTotalNumberOfNotifications()
{
	const currentTotalNotification = document.querySelector('.js-total-notifications').textContent
	document.querySelectorAll('.js-total-notifications').forEach(element=>{
		element.innerHTML = parseInt(currentTotalNotification) + 1 
	})
}
function resetTotalNumberOfNotifications()
{
	document.querySelectorAll('.js-total-notifications').forEach(element=>{
		element.innerHTML = 0
	})
}

function prependNewNotification(message , title,createdAtFormatted)
{
	const messageDiv =  `<a href="javascript:void(0)">
	<div class="media">
		<div class="media-left align-self-center"><i class="ft-plus-square icon-bg-circle bg-cyan"></i></div>
		<div class="media-body">
			<h6 class="media-heading">${title}</h6>
			<p class="notification-text font-small-3 text-muted">${message}</p>
			<small>
				<time class="media-meta text-muted">${createdAtFormatted}</time>
			</small>
		</div>
	</div>
	</a>`;
	
	$('#js-notification-prepend').prepend(messageDiv);
	
}

$(document).on('click','#mark-notifications-as-read',function(){
	resetTotalNumberOfNotifications()
	const adminId = $('body').attr('data-admin-id')
	const url = route('mark.notifications.as.read',{admin:adminId})
	const token = $('body').attr('data-token')
	const type = 'put';
	$.ajax({
		url,
		data:{
			"_token":token
		},
		type
	})
})


const adminId = $('body').attr('data-admin-id')
const lang = $('body').attr('data-lang')
window.Echo.private('App.Models.Admin.' + adminId)
    .notification((notification) => {
		const title = notification['title_'+lang]
		const message = notification['message_'+lang]
		toastr.success(message, title)
		prependNewNotification(message, title,notification.createdAtFormatted)
		incrementTotalNumberOfNotifications()
    });
	
// window.Echo.channel('channelname')
//     .listen('TestEvent', (e) => {
//         console.log(e);
// 		toastr.success('قام احمد صلاح بانشاء مشروع جديد', 'تم انشاء منشور جديد')
//     });





    
