
jQuery.fn.extend({

    tabs: function() {
        /*
        * TABS
        * tabs formating and manipulation
        * usage snipet:
                <div id="tabsId">
                    <ul>
                        <li href="#tabsId-1"> Tab1 </li>
                        <li href="#tabsId-2"> Tab2 </li>
                        <!-- add tabs as needed -->
                    </ul>
                    <div id="tabsId-1"> Tab1 content </div>
                    <div id="tabsId-2"> Tab2 content </div>
                    <!-- <div> "id" and <li> "href" must match -->
                </div>
              <!-- add to script -->
                <script>
                    $(function() {
                        // [...]
                        $( "#tabsId" ).tabs();
                        // [...]
                    })
                </script>
        * -TkT
        */
        this.addClass("ui-tabs");
        this.children("ul").first().addClass("ui-tabsList").children("li").each(function(){
            $(this).addClass("ui-tabsTab");
            $(this).on("click",function(){
                if(!$(this).hasClass("ui-activ")){
                    $(this).addClass("ui-activ");
                    $(this).siblings().removeClass("ui-activ");
                    $(this).parent().siblings().removeClass("ui-activ");
                    $($(this).attr("href")).addClass("ui-activ");
                }
            });
        }).first().addClass("ui-activ");
        this.children("div").addClass("ui-tabsContent").first().addClass("ui-activ");
    },



    popup: function() {
        /*
        * POPUP
        * draggable popup element formating and manipulation
        * usage snipet:
              <!-- popup activator -->
                <a class="ui-popupBtn" asoc="#popupId">btnText</a>
              <!-- popup -->
                <div id="popupId">
                  <span>Popup Title</span> <!-- optional -->
                  <!-- [popup content] -->
                </div>
              <!-- add to script -->
                <script>
                    $(function() {
                        // [...]
                        $( ["#popupId", "#popupId2"] ).popup();
                        // [...]
                    })
                </script>
        * -TkT
        */
      //popup formating
        this.each(function(){
          var popupID = "";
          $(this).each(function() {
            popupID += this;
          });
          $(popupID).addClass("ui-popup");
          if($(popupID).children().first().is("span")){
            $(popupID).children().first().wrap("<div class='ui-popupHead'></div>");
          }else{
            $("<div class='ui-popupHead'><span></span></div>").prependTo($(popupID));
          }
          $("<div><span>&#10021; </span> <a class='btn delete ui-popupBtn' asoc='#" + $(popupID).attr("id") + "'>&#9587;</a></div>").appendTo($(popupID).children().first());

          
          var pos1 = 0, pos2 = 0, pos3 = 0, pos4 = 0, elmnt = $(popupID)[0];
          elmnt.querySelector(".ui-popupHead").onmousedown = dragMouseDown;
          

          function dragMouseDown(e) {
            e.preventDefault();
            // get the mouse cursor position at startup:
            pos3 = e.clientX;
            pos4 = e.clientY;
            document.onmouseup = closeDragElement;
            // call a function whenever the cursor moves:
            document.onmousemove = elementDrag;
          }

          function elementDrag(e) {
            // calculate the new cursor position:
            pos1 = pos3 - e.clientX;
            pos2 = pos4 - e.clientY;
            pos3 = e.clientX;
            pos4 = e.clientY;
            // set the element's new position:
            elmnt.style.left = (0 < (elmnt.offsetLeft - pos1) && (elmnt.offsetLeft - pos1) < (window.innerWidth - elmnt.offsetWidth)) ? (elmnt.offsetLeft - pos1) + "px" : elmnt.offsetLeft + "px";
            elmnt.style.top = (0 < (elmnt.offsetTop - pos2) && (elmnt.offsetTop - pos2) < (window.innerHeight - elmnt.offsetHeight)) ? (elmnt.offsetTop - pos2) + "px" : elmnt.style.top + "px";

            

            /* stop element from being off frame:
              // top
            if(elmnt.offsetTop < -1){
              elmnt.style.paddingTop = -1 - elmnt.offsetTop + "px";
            }else{
              elmnt.style.paddingTop = "0px";
            }
              // left
            if(elmnt.offsetLeft < -1){
              elmnt.style.paddingLeft = -1 - elmnt.offsetLeft + "px";
            }else{
              elmnt.style.paddingLeft = "0px";
            }
              //right
            if(elmnt.offsetLeft > (window.innerWidth - elmnt.offsetWidth) ){
              elmnt.style.paddingRight = elmnt.offsetLeft - window.innerWidth + elmnt.offsetWidth + "px";
            }else{
              elmnt.style.paddingRight = "0px";
            }*/
          }

          function closeDragElement() {
            // stop moving when mouse button is released:
            document.onmouseup = null;
            document.onmousemove = null;
          }


        });
      //popup btn
        $(".ui-popupBtn").click(function() {
          console.log("bip");
          var linkedPopup = $(this).attr("asoc");
          $(linkedPopup).css("top", $(this).offset().top + "px") ;
          $(linkedPopup).css("left", ($(window).width() - $(linkedPopup).width() )/2 + "px");
          $(linkedPopup).toggleClass("ui-activ");
        });
      //dragging popup
  
    },

});