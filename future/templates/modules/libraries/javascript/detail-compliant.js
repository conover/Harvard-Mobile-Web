function toggleBookmark(elem, id, cookie) {
  var bookmarks = getBookmarks(cookie);
  var index = bookmarks.indexOf(id);
  
  if (elem.className == 'bookmarked') {
    if (index != -1) {
      bookmarks.splice(index, 1);
    }
    elem.className = '';
  } else {
    if (index == -1) {
      bookmarks.push(id);
    }
    elem.className = 'bookmarked';
  }
  setBookmarks(bookmarks, cookie);
}

function getBookmarks(cookie) {
  var bookmarkString = getCookie(cookie);
  if (bookmarkString.length) {
    return bookmarkString.split(',');
  } else {
    return new Array();
  }
}

function setBookmarks(bookmarks, cookie) {
  setCookie(cookie, bookmarks.join(','));
}
