
async function jsonToObj(source) {
    /*
    * jsonToObj
    * parse url to json data to object
    * usage snipet:
          <!-- add to script -->
            <script>
                $(function() {
                    // [...]
                    $().jsonToObj("api/object/source");
                    // [...]
                })
            </script>
    * -TkT
    */
      // Generate the Response object
      const response = await fetch(source);
      if (response.ok) {
        // Get JSON value from the response body
        //console.log("responce: " + response);
        return response.json();
      }
      throw new Error("*** PHP file not found");
}




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

          
          var elmnt = $(popupID)[0], elmPoseX, elmPoseY;
          elmnt.querySelector(".ui-popupHead").onmousedown = dragMouseDown;
          

          function dragMouseDown(e) {
            e.preventDefault();
            // get the mouse cursor position at startup:
            elmPoseX = elmnt.offsetLeft - e.clientX; 
            elmPoseY = elmnt.offsetTop - e.clientY;
            document.onmouseup = closeDragElement;
            // call a function whenever the cursor moves:
            document.onmousemove = elementDrag;
          }

          function elementDrag(e) {
            // calculate the new cursor position:
            // set the element's new position:
            if(0 > (e.clientX + elmPoseX)){
              elmnt.style.left = "0px";
            }else if((e.clientX + elmPoseX) > (window.innerWidth - elmnt.offsetWidth)){
              elmnt.style.left = window.innerWidth - elmnt.offsetWidth + "px";
            }else{
            elmnt.style.left = e.clientX + elmPoseX + "px"
            }

            if(0 > (e.clientY + elmPoseY)){
              elmnt.style.top = "0px";
            }else if((e.clientY + elmPoseY) > (window.innerHeight - elmnt.offsetHeight)){
              elmnt.style.top = window.innerHeight - elmnt.offsetHeight + "px";
            }else{
            elmnt.style.top = e.clientY + elmPoseY + "px"
            }
          }

          function closeDragElement() {
            // stop moving when mouse button is released:
            document.onmouseup = null;
            document.onmousemove = null;
          }


        });
      //popup btn
        $(".ui-popupBtn").click(function() {
          var linkedPopup = $(this).attr("asoc");
          $(linkedPopup).css("top", $(this).offset().top + "px") ;
          $(linkedPopup).css("left", ($(window).width() - $(linkedPopup).width() )/2 + "px");
          $(linkedPopup).toggleClass("ui-activ");
        });
      //dragging popup
  
    },



    jsonObjTable: function(objSource) {
        /*
        * jsonObjTable
        * parse json object to html table
        * usage snipet:
              <!-- htmlTable -->
                <tbody id="tableId">
                  <tr>
                    <td>#tableId.key1#</td>
                    <td>#tableId.key2#</td>
                    <!-- add keys as needed -->
                  </tr>
                </tbody>
              <!-- add to script -->
                <script>
                    $(function() {
                        // [...]
                        $( "#tableId").jsonToObj("api/object/route");
                        // [...]
                    })
                </script>
        * -TkT
        */
      //table object itteration
        var data = jsonToObj(objSource);
        template = $("<div />").append($(this).children());
        data.then((result) => {
            result.forEach((element) => {
              i = (template).clone();
              $(i).jsonObjParse($(this).attr("id"), element);
              

              $(i).children().appendTo($(this));
            });
          })
          .catch((error) => console.error(error));
        //data.forEach((element) => console.log(element));
    },



    jsonObjReplace: function(identifier, objSource) {
        /*
        * jsonObjTable
        * parse json object to html table
        * usage snipet:
              <!-- htmlTable -->
                <tbody id="tableId">
                  <tr>
                    <td>#tableId.key1#</td>
                    <td>#tableId.key2#</td>
                    <!-- add keys as needed -->
                  </tr>
                </tbody>
              <!-- add to script -->
                <script>
                    $(function() {
                        // [...]
                        $( "#tableId").jsonToObj("api/object/route");
                        // [...]
                    })
                </script>
        * -TkT
        */
      //table object itteration
        var data = jsonToObj(objSource);
        data.then((result) => {
            $(this).jsonObjParse(identifier, result);
          })
          .catch((error) => console.error(error));
    },



    jsonObjParse: function(identifier, object) {
        /*
        * jsonObjReplace
        * parse json object to html table
        * usage snipet:
              <!-- htmlBody -->
                <tbody id="tableId">
                  <tr>
                    <td>#tableId.key1</td>
                    <td>#tableId.key2</td>
                    <!-- add keys as needed -->
                  </tr>
                </tbody>
              <!-- add to script -->
                <script>
                    $(function() {
                        // [...]
                        $( "#tableId", obj ).jsonToObj();
                        // [...]
                    })
                </script>
        * -TkT
        */
      //table object itteration
        //console.log(object);
        $(this).html((this).html().replace(new RegExp('#' + identifier + '\\.(.[^# ]+)#', 'g'), function(match, p1) {
          var result = object;
          p1.split(".").forEach((element) => result = result[element]);
          //console.log(result);
          
          return result;
        }));
    },

});