// Main controller
app.controllers.main = {
	// Parser redirect
	redirect: function(url) {
		app.route.search(url);
	},

	// Main page
	main_page: function() {
		// Preloader
		app.popup.preloader();
		
		// Get request
		app.request.get(data => {
			// Content processing
			content = app.controllers.main.main_content(data.data);

			// Get template
			app.template.get_template("home/home");
			app.template.set_value({
				"TITLE": "New Ongoing Release",
				"URL": app.config.url,
				"CONTENT": content,
			});

			// Out content
			app.html.content.innerHTML = app.template.get_content();

			// Hide preloader
			app.popup.hide();
		}, "api/main?url="+app.config.url);
	},

	// Novels page
	novels_page: function() {
		// Get page
		if(!app.route.var.page) page = 1;
		else page = app.route.var.page;

		// Preloader
		app.popup.preloader();

		// Get request
		app.request.get(data => {
			// Content processing
			content = app.controllers.main.novels_content(data.data);

			// Paginator processing
			paginator = app.controllers.main.paginator_processing(page);

			// Get template
			app.template.get_template("novels/novels");
			app.template.set_value({
				"URL": app.config.url,
				"PAGE": page,
				"CONTENT": content,
				"PAGINATOR": paginator
			});

			// Out content
			app.html.content.innerHTML = app.template.get_content();
			document.querySelectorAll(".paginator div").forEach(elem => {
				if(elem.innerHTML == page) return elem.classList.add("active");
			});

			// Hide preloader
			app.popup.hide();
		}, "/api/novels/"+page+"?url="+app.config.url+"genre/all/popular/all/"+page);
	},

	// Novel page
	novel_page: function() {
		// Get id
		if(!app.route.var.id) return app.route.not_found();
		id = app.route.var.id;
		
		// Preloader
		app.popup.preloader();

		// Get request
		app.request.get(data => {
			console.log(data);

			app.template.get_template("novel/novel");
			app.template.set_value({
				"SRC": data.data[0],
				"TITLE": data.data[1]
			});
			app.html.content.innerHTML = app.template.get_content();

			// Hide preloader
			app.popup.hide();
		}, "/api/novel/"+id+"?url="+app.config.url+"novel/"+id);
	},

	// Get main page data
	main_content: function(data) {
		let result = [];
		data.forEach(content => {
			result.push({
				name: content[1].substring(0, 29),
				url: content[2],
				src: content[3],
			});
		});
		return app.controllers.main.content_processing(result);
	},

	// Get novels page data
	novels_content: function(data) {
		let result = [];
		data.forEach(content => {
			result.push({
				name: content[0].substring(0, 29),
				url: content[3],
				src: content[4],
			});
		});
		return app.controllers.main.content_processing(result);
	},

	// Content processing
	content_processing: function(data) {
		app.template.get_template("item-content", data.length);
		data.forEach(content => {
			app.template.set_value({
				"NAME": content.name,
				"URL": content.url,
				"SRC": content.src
			}); app.template.get_content();
		}); return app.template.get_content();
	},

	// Paginator processing
	paginator_processing: function(page) {
		number = 5;
		app.template.get_template("novels/paginator", number);
		if(page > 2) count = page - 2; else if(page <= 1) count = page; else count = page - 1;
		for(let i = count; i < count + number; i++) {
			app.template.set_value({
				"PAGE": i,
				"N": i,
			});
			app.template.get_content();
		}
		return app.template.get_content();
	},
}