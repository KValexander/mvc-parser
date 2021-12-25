// Routes
// Main page
app.route.add_route("/", "main@main_page");

// Novels page
app.route.add_route("/novels", "main@novels_page");
app.route.add_route("/novels/{page}", "main@novels_page");

// Novel page
app.route.add_route("/novel/{id}", "main@novel_page");

// Chapter page
app.route.add_route("/novel/{id}/{chapter}", "main@chapter_page");
