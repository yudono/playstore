const endpoint_all = 'https://playstore.axialapp.site/Backend/'
const endpoint_detail_short = 'https://playstore.axialapp.site/Backend/short_view.php?view='
const endpoint_detail = 'https://playstore.axialapp.site/Backend/view.php?view='
const endpoint_search = 'https://playstore.axialapp.site/Backend/search.php?q='

const re_loading = document.querySelector('#recommend-loading')
const re_box = document.querySelector('#recommend-box-scroll')
const ne_loading = document.querySelector('#news-loading')
const ne_box = document.querySelector('#news-box-scroll')
const search_box = document.querySelector('#list-search')
const home_page = document.querySelector('#home-page')
const search_page = document.querySelector('#search-page')

async function fetch_all() {
	const data = await fetch(endpoint_all).then(e=>e.json())

	let html = ['','']

	let per_box = data.length /2
	let i = 0

	for(e of data) {

		if(i <= per_box) {
			html[0] += `<div class="w-24 inline-block mr-4 flex-shrink-0" onclick="view('${e.url}')">
				<img src="${e.icon}" alt="game" class="w-24 h-24 rounded-lg object-cover">
				<div class="text-sm mt-2 roboto leading-tight whitespace-normal">${e.name}</div>
			</div>`
		}else{
			html[1] += `<div class="w-24 inline-block mr-4 flex-shrink-0" onclick="view('${e.url}')">
				<img src="${e.icon}" alt="game" class="w-24 h-24 rounded-lg object-cover">
				<div class="text-sm mt-2 roboto leading-tight whitespace-normal">${e.name}</div>
			</div>`
		}
		i++

	}

	re_box.innerHTML = html[0]
	ne_box.innerHTML = html[1]

	re_loading.classList.add('hidden')
	re_box.classList.remove('hidden')
	ne_loading.classList.add('hidden')
	ne_box.classList.remove('hidden')
}

async function search(search = '') {
	const data = await fetch(endpoint_search+search).then(e=>e.json())
	let html = ''
	
	for(e of data) {
		html += `<div class="flex flex-row px-8 py-3" onclick="view('${e.url}')">
					<img src="${e.icon}" alt="icon" class="w-14 h-14 object-cover rounded-lg flex-shrink-0 mr-4">
					<div>
						<div class="roboto">${e.name}</div>
						<div class="roboto text-xs text-gray-400">${e.star} - ${e.publisher}</div>
					</div>
				</div>`
	}

	search_box.innerHTML = html

}

async function view(id) {
	let view = document.querySelector('#view-app')
	let icon = document.querySelector('#icon-app')
	let title = document.querySelector('#title-app')
	let publisher = document.querySelector('#publisher-app')
	let thumbnails = document.querySelector('#thumbnail-app')
	let download = document.querySelector('#download-app')

	view.classList.remove('hidden')

	let data = await detail(id)
	// '/arena-of-valor-strike-of-kings/com.ngame.allstar.eu'
	// let data = await detail('/microsoft-office-word-excel-powerpoint-more/com.microsoft.office.officehubrow')
	icon.setAttribute('src', data.icon)
	title.innerHTML = data.name
	publisher.innerHTML = data.publisher
	download.setAttribute('href', data.download)

	let _tt = ''
	if(data.thumbnail.youtube != undefined) {
		_tt += `<div class="relative mx-2 inline-block" onclick="openpopup('${data.thumbnail.embed}')">
						<img src="${data.thumbnail.youtube}" alt="${data.thumbnail.youtube}" class="h-36 object-cover inline-block rounded-lg">
						<ion-icon name="play-outline" class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 text-white text-6xl"></ion-icon>
					</div>`
	}
	data.thumbnail.images.forEach(e=>{
		_tt += `<img src="${e}" alt="${e}" class="h-36 object-cover inline-block mx-2 rounded-lg">`
	})
	thumbnails.innerHTML = _tt
}

async function detail(id) {
	const data = await fetch(endpoint_detail+id).then(e=>e.json())
	return data
}

function openpopup(e) {
	document.querySelector('#popup').classList.remove('hidden')
	document.querySelector('#popup iframe').setAttribute('src', e)
}
function closepopup() {
	document.querySelector('#popup').classList.add('hidden')
}

function closeview() {
	document.querySelector('#view-app').classList.add('hidden')	
}

function search_input(e) {
	if(e.value.length > 0) {
		home_page.classList.add('hidden')
		search_page.classList.remove('hidden')
		// if(event.keyCode == 13) {
			search_box.innerHTML = `<div class="w-full mt-8" id="news-loading">
					<img src="img/loading.gif" alt="loading" class="w-8 mx-auto">
				</div>`
			search(e.value)
		// }
	}else{
		// close search page
		home_page.classList.remove('hidden')
		search_page.classList.add('hidden')
	}
}

window.addEventListener('load', function() {
	load()
})
window.addEventListener('resize', function() {
	load()
})
function load() {
	if(window.innerWidth < 640) {
		fetch_all()
	}
}