# php-fileviewer
It's a simple fileviewer in php. It can list directorys, show files and download them. So that's it 4 the moment.


# Config
You can set a password with the `$lock` variable. You can comment out the `$lock`-variable to disable the password protection.
If you rename the file don't forget to change the `$thisfile` variable.
The variable `$dir` is the path of the root-directory the filemanager should show.


# Translation
Feel free to translate this stuff. 

_Languages I support at the moment:_
  * English
  * German


# TODO
- [ ] Security patch (u know, unencrypted password -> make some SHA512-STUFF, set variables -> do some `unset()`)
- [ ] File Uploader
- [ ] File Editor
- [ ] add more languages (help is appreciated)
- [ ] add more TODO
- [ ] Thank [@mondanzo](https://github.com/mondanzo) 4 annoy me with security stuff. 
