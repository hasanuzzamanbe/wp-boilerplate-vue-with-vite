const glob = require('glob');
const fs = require('fs');

// For entry file selection
glob("plugin-entry.php", function(err, files) {
        files.forEach(function(item, index, array) {
            const data = fs.readFileSync(item, 'utf8');
            const mapObj = {
                PLUGIN_CONST_DEVELOPMENT: "PLUGIN_CONST_PRODUCTION"
            };
            const result = data.replace(/PLUGIN_CONST_DEVELOPMENT/gi, function (matched) {
                return mapObj[matched];
            });
            fs.writeFile(item, result, 'utf8', function (err) {
            if (err) return console.log(err);
        });
        console.log('✅  Development asset enqueued!');
    });
});