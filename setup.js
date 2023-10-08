var readline = require('readline');

var rl = readline.createInterface({
  input: process.stdin,
  output: process.stdout
});

function convertToSlug(Text)
{
    return Text
        .toLowerCase()
        .replace(/ /g,'-')
        .replace(/[^\w-]+/g,'')
        ;
}

function convertToLowercase(Text)
{
    return Text
        .toLowerCase()
        .replace(/ /g,'')
        .replace(/[^\w-]+/g,'')
        ;
}

function camalize(str) {
  return str.toLowerCase().replace(/[^a-zA-Z0-9]+(.)/g, function(match, chr)
  {
      return chr.toUpperCase();
  });
}

function convertToUpperCamel(Text)
{
    return Text
        .to()
        .replace(/ /g,'')
        .replace(/[^\w-]+/g,'')
        ;
}

function convertToUppercase(Text)
{
    return Text
        .toUpperCase()
        .replace(/ /g,'')
        .replace(/[^\w-]+/g,'')
        ;
}


rl.question("Please enter your plugin Name:", function(answer) {

  if(!answer.includes("-")){
    answer = answer.replace(/\s+$/, '');
    var glob = require('glob');
    var fs = require('fs');
    
  
    // For entry file selection
    glob("wp-plugin-with-vue-tailwind.php", function(err, files) {
          files.forEach(function(item, index, array) {
            
            var data = fs.readFileSync(item, 'utf8');
            var Uppercase = convertToUppercase(answer);
            var Lowercase = convertToLowercase(answer);
            var Slug      = convertToSlug(answer);
            var Camel     = camalize(answer);
        
            var mapObj = {
              WPPluginVueTailwind: Camel,
              WPWVT: Slug,
              WPM: Uppercase,
              WPPluginVueApp: answer
           };
           var result = data.replace(/WPPluginVueTailwind|WPWVT|WPM|WPPluginVueApp/gi, function(matched){
             return mapObj[matched];
           });
            fs.writeFile(item, result, 'utf8', function (err) {
                if (err) return console.log(err);
            });
            console.log('✅ Plugin Entry file generated.');
        });
    });
  
    glob("includes/autoload.php*", function(err, files) {
  
      files.forEach(function(item, index, array) {
            
                var data = fs.readFileSync(item, 'utf8');
                var Uppercase = convertToUppercase(answer);
                var Lowercase = convertToLowercase(answer);
                var Slug      = convertToSlug(answer);
                var Camel     = camalize(answer);
            
                var mapObj = {
                  WPPluginVueTailwind: Camel,
                  WPWVT: Slug,
                  WPM: Uppercase,
                  WPPluginVueApp: answer
               };

               var result = data.replace(/WPPluginVueTailwind|WPWVT|WPM|WPPluginVueApp/gi, function(matched){
                 return mapObj[matched];
               });
                fs.writeFile(item, result, 'utf8', function (err) {
                    if (err) return console.log(err);
                });
                console.log('✅ Class making Successful !');
            });
    });
  
    // Find file(s) except node and entry
    glob("!(node_modules)/*/*.*", function(err, files) {
        if (err) { throw err; }
    
        files.forEach(function(item, index, array) {
  
              // Read fileclear
              var data = fs.readFileSync(item, 'utf8');
  
         
              var Uppercase = convertToUppercase(answer);
              var Lowercase = convertToLowercase(answer);
              var Slug      = convertToSlug(answer);
              var Camel     = camalize(answer);
           
  
              var mapObj = {
                YourPlugin: answer,
                WPPluginVueTailwind: Camel,
                WPWVT: Slug,
                WPM: Uppercase,
                WPPluginVueApp: answer
             };
             var result = data.replace(/YourPlugin|WPPluginVueTailwind|WPWVT|WPM|WPPluginVueApp/gi, function(matched){
               return mapObj[matched];
             });
    
              fs.writeFile(item, result, 'utf8', function (err) {
                  if (err) return console.log(err);
              });
              console.log('✅ file:('+item +') '+'=>Generated');
          });
          console.log(` 
      
    _______ _______ _______ ________        _______________________ ______  
   (  ____ (  ___  (       (  ____ ( \      (  ____  \__   __(  ____ (  __  ) 
   | (    \ | (   ) | () () | (    )| (     | (     \/  ) (  | (     \| (  )  )
   | |     | |   | | || || | (____)| |     | (__      | |  | (__   | |   ) |
   | |     | |   | | |(_)| |  _____| |     |  __)     | |  |  __)  | |   | |
   | |     | |   | | |   | | (     | |     | (        | |  | (     | |   ) |
   | (____/| (___) | )   ( | )     | (____/| (____/\   | |  | (____/| (__/  )
   (_______(_______|/     (|/      (_______(_______/  )_(  (_______(______/ 
                                                                                                            
          All File Processed Successfully!
          Now run "npm run watch" and activate your plugin.
          Thanks from https://www.hasanuzzaman.com`)
    });
  
    fs.unlink('_config.yml', (err) => {
      if (err) {
        console.log('✅ No unused file here')
        return
      }
      console.log('✅ unused file removed');
    })

    // Closing all inputs
    rl.close();
  }else {
    var suggestion = answer.replace(/-/g, ' ');
    console.log('⚠️ Warning: Please don\'t use hyfen. You may use '+ suggestion + ' as your plugin name');
    console.log('⚠️ Please run again "node src/seup" and enter a unique plugin name.');
  }
 
 
});

