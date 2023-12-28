function initGallery(){
  var gallery = document.querySelector('#gallery');
  if(gallery.children.length) return;
  var progress = document.createElement('progress');
  gallery.appendChild(progress);

  var recursive = confirm('Show images in child directories as well?');
  // values have attributes: src, w, h
  var result = [];
  var pending = [];
  var running = 0;
  var done = false;

  function parseTable(tbody, path){
    // Get all links except parent directory
    var rows = tbody.querySelectorAll('tr td a:not([href="../"])');
    rows.forEach(function (row){
      let url = path + row.getAttribute('href');
      if(url.substr(-1) === '/'){
        if(recursive)
          // Add the folder
          pending.push({type:'dir', path:url});
        return;
      }
      let ext = url.split('.');
      ext = ext[ext.length - 1];
      if(['jpeg', 'jpg', 'gif', 'png', 'webp'].indexOf(ext.toLowerCase()) === -1)
        return;
      // Add the image
      pending.push({type:'img', path:url});
    });
    process();
  }

  function process(){
    progress.max = pending.length + result.length + running;
    progress.value = result.length + running;
    for(let i=running; i<4; i++)
      processItem();
  }

  function open(item){
    if(!done) return;
    // Init photoswipe
    var pswpElement = document.querySelectorAll('.pswp')[0];
    var options = {
        index:item.index,
        getThumbBoundsFn: function(index) {
          var thumbnail = result[index].el.getElementsByTagName('img')[0], // find thumbnail
          pageYScroll = window.pageYOffset || document.documentElement.scrollTop,
          rect = thumbnail.getBoundingClientRect();
          return {x:rect.left, y:rect.top + pageYScroll, w:rect.width};
      }
    }

    var psp = new PhotoSwipe( pswpElement, PhotoSwipeUI_Default, result, options);
    psp.init();
  }

  function processItem(){
    let item = pending.pop();
    if(!item){
      if(running === 0 && !done){
        done = true;
      }
      return;
    }
    let {path} = item;
    if(item.type === 'img')
      path += "/89368e1d68015693ab48ee189d0632cb5d6edfb3";
    running++;
    fetch(path).then((data) => {
      if(item.type === 'img'){
        let p = data.json().then((data) => {
          running--;
          data = data.img;
          if(!data) return;
          let image = {h:data.height, w:data.width, src:item.path};
          image.msrc = item.path + (image.h > image.w ? '/20c69262da6d3ab1f8d5ad62f460645a2cc9ae8d' : '/89f6229a11ac4ebaa553c1a3ea96d78fa7483735');
          var img = document.createElement('img');
          img.src= image.msrc;
          var wrapper = document.createElement('span');
          wrapper.index = result.length;
          wrapper.appendChild(img);
          wrapper.onclick = function(){ open(wrapper) };
          gallery.appendChild(wrapper);
          image.el = wrapper;
          result.push(image);
          process();
        }, () => { running--; });
      }
      else {
        data.text().then((src) => {
          running--;
          let parser = document.createElement('html');
          parser.innerHTML = src;
          parser = parser.querySelector('table tbody');
          parseTable(parser, path);
        }, () => { running--; });
      }
    });
  }
  // Start with this page
  parseTable(document.querySelector('table tbody'), window.location.pathname);
}

function checkAnchor(){
  if(window.location.hash === '#gallery'){
    window.location.hash = "";
    initGallery();
  }
}

window.onload = checkAnchor;
window.onhashchange = checkAnchor;
