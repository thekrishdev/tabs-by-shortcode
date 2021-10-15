document.addEventListener('DOMContentLoaded', function() {
	if(window.location.hash && document.querySelector('.tabs-nav a[href="'+decodeURI(window.location.hash)+'"]')) {
		var tabs = document.querySelector('.tabs-nav a[href="'+decodeURI(window.location.hash)+'"]').closest('.tabs-container');
		tabs.querySelector('.tabs-nav a.active').removeAttribute('class');
		tabs.querySelector('section.tab.active').classList.remove('active');
		tabs.querySelector('.tabs-nav a[href="'+decodeURI(window.location.hash)+'"]').classList.add('active');
		tabs.querySelector('section'+decodeURI(window.location.hash)+'.tab').classList.add('active');
	}
	document.querySelectorAll('.tabs-nav a, a.tab-url').forEach(function(item) {
		item.addEventListener('click', function(event) {
			event.preventDefault();
			this.closest('.tabs-container').querySelector('.tabs-nav a.active').removeAttribute('class');
			this.closest('.tabs-container').querySelector('section.tab.active').classList.remove('active');
			this.closest('.tabs-container').querySelector('.tabs-nav a[href="'+this.getAttribute('href')+'"]').classList.add('active');
			this.closest('.tabs-container').querySelector('section'+this.getAttribute('href')+'.tab').classList.add('active');
		});
	});
});