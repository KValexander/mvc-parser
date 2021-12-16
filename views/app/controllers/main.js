// Main controller
app.controllers.main = {
	// Parser redirect
	redirect: function(url) {
		console.log(url);
	},

	// Main page
	main_page: function() {
		// Preloader
		app.popup.preloader();
		
		// Get request
		app.request.get(data => {
			// Content processing
			content = app.controllers.main.main_page_content_processing(data.data);

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
			content = app.controllers.main.novels_page_content_processing(data.data);

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

	// Content processing
	main_page_content_processing: function(data) {
		app.template.get_template("item-content", data.length);
		data.forEach(content => {
			app.template.set_value({
				"NAME": content[1].substring(0, 29),
				"URL": content[2],
				"SRC": content[3]
			}); app.template.get_content();
		}); return app.template.get_content();
	},

	// Content processing
	novels_page_content_processing: function(data) {
		app.template.get_template("item-content", data.length);
		data.forEach(content => {
			app.template.set_value({
				"NAME": content[0].substring(0, 29),
				"URL": content[3],
				"SRC": content[4]
			}); app.template.get_content();
		}); return app.template.get_content();
	},

	// Paginator processing
	paginator_processing: function(page) {
		app.template.get_template("novels/paginator", 5);
		if(page > 2) count = page - 2; else if(page <= 1) count = page; else count = page - 1;
		for(let i = count; i < count + 4; i++) {
			app.template.set_value({
				"PAGE": i,
				"N": i,
			});
			app.template.get_content();
		}
		count = (page > 2) ? parseInt(page) + 2 : count = parseInt(page) + 3;
		app.template.set_value({
			"PAGE": count,
			"N": count
		});
		return app.template.get_content();
	},
}