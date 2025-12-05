function unwrap(coll) {
    /*
    * unwrap
    * unwrap collapser content
    * usage snipet:
          <!-- html -->
            <div onclick="unwrap(this);">
                dummy title
            </div>
            <div class="wrapper">
              dummy
            </div>
    * -TkT
    */
      var content = coll.nextElementSibling;
      content.classList.toggle("is-open");
}


function selectUnwrap(coll) {
    /*
    * unwrap
    * unwrap collapser content and wrap all others
    * usage snipet:
          <!-- html -->
            <div onclick="selectUnwrap(this);">
                dummy title
            </div>
            <div class="selectWrapper">
              dummy
            </div>
    * -TkT
    */
      var content = coll.nextElementSibling;
      if (content.classList.contains("is-open")) {
          content.classList.toggle("is-open");
      } else {
          var allColl = document.getElementsByClassName("selectWrapper");
          for (i = 0; i < allColl.length; i++) {
              allColl[i].classList.remove("is-open");
          }
          content.classList.toggle("is-open");
      }
}


