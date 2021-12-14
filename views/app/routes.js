// Routes
app.route.add_route("/", "main@main_page");
app.route.add_route("/novels", "main@novels_page");
app.route.add_route("/novels/{page}", "main@novels_page");
