
async function urlToJsonObj(source) {
    /*
    * urlToJsonObj
    * parse url to json data to object
    * usage snipet:
          <!-- add to script -->
            <script>
                $(function() {
                    // [...]
                    urlToJsonObj("api/object/source");
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



async function apiDeleteObj(source, redirectURL = null) {
    /*
    * apiDeleteObj
    * delete object via api and get responce
    * usage snipet:
            // [...]
            <a class="btn delete" onclick="apiDeleteObj('{{ path("api_contact_delete", {"id": id}) }}', '{{ path("app_contact_index") }}');">Delete</a>
            // [...]
    * -TkT
    */
  //fonction start:
    //confirm delete
    if(confirm("Êtes-vous sur de vouloirs supprimés cet objet ?")){
      //delete request
      await fetch(source, { method: 'DELETE' })
        .then((response) => {
          if(response.status === 204){
            //redirect if needed
            if(redirectURL) open(redirectURL, "_self");
          }
        })
        .catch((error) => console.error(error));
    }
}



function uiPopupBtn(linkedPopup) {
    /*
    * uiPopupBtn
    * popup btn handler
    * usage snipet:
            // [ look at "popup: function()" below ]
    * -TkT
    */
  //fonction start:
    $(linkedPopup).css("top", ($(window).height() - $(linkedPopup).height())/2 + "px") ;
    $(linkedPopup).css("left", ($(window).width() - $(linkedPopup).width() )/2 + "px");
    $(linkedPopup).toggleClass("ui-activ");
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
      //function start:
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
                <a onclick="uiPopupBtn('#popupId')">btnText</a>
              <!-- popup -->
                <div id="popupId">
                  <span>Popup Title</span> <!-- optional -->
                  <!-- [popup content] -->
                </div>
              <!-- add to script -->
                <script>
                    $(function() {
                        // [...]
                        $( "#popupId" ).popup();
                        // [...]
                    })
                </script>
        * -TkT
        */
      //function start:
        //popup formating
        $(this).addClass("ui-popup");
        if($(this).children().first().is("span")){
          $(this).children().first().wrap("<div class='ui-popupHead'></div>");
        }else{
          $("<div class='ui-popupHead'><span></span></div>").prependTo($(this));
        }
        $("<div><span>&#10021; </span> <a class='btn delete' onclick=\"uiPopupBtn('#" + $(this).attr("id") + "')\">&#9587;</a></div>").appendTo($(this).children().first());


        
        //initialize drag functions variables
        var elmnt = $(this)[0], elmPoseX, elmPoseY;
        //call drag function on popup head mousedown
        elmnt.querySelector(".ui-popupHead").onmousedown = dragMouseDown;
        
        //drag handler
        function dragMouseDown(e) {
          e.preventDefault();
          //get the mouse cursor position at startup
          elmPoseX = elmnt.offsetLeft - e.clientX; 
          elmPoseY = elmnt.offsetTop - e.clientY;
          //stop handling when mouse button is released
          document.onmouseup = closeDragElement;
          //whenever the cursor moves, call elementDrag function
          document.onmousemove = elementDrag;
        }

        //element position handler
        function elementDrag(e) {
          //handle horizontal position and stop popup from going out of viewport
          if(0 > (e.clientX + elmPoseX)){
            elmnt.style.left = "0px";
          }else if((e.clientX + elmPoseX) > (window.innerWidth - elmnt.offsetWidth)){
            elmnt.style.left = window.innerWidth - elmnt.offsetWidth + "px";
          }else{
          elmnt.style.left = e.clientX + elmPoseX + "px"
          }

          //handle vertical position and stop popup from going out of viewport
          if(0 > (e.clientY + elmPoseY)){
            elmnt.style.top = "0px";
          }else if((e.clientY + elmPoseY) > (window.innerHeight - elmnt.offsetHeight)){
            elmnt.style.top = window.innerHeight - elmnt.offsetHeight + "px";
          }else{
          elmnt.style.top = e.clientY + elmPoseY + "px"
          }
        }

        //stop listening to movement
        function closeDragElement() {
          document.onmouseup = null;
          document.onmousemove = null;
        }
    },



    ajaxForm: function() {
        /*
        * ajaxForm
        * send form data via ajax
        * usage snipet:
              <!-- htmlBody -->
                <form name="contact_form" method="post" action="{{ path('api_contact_index') }}/{{ edit|default('new') }}">
                  <div>
                      <label for="contact_form_nom">Nom</label>
                      <input type="text" id="contact_form_nom" name="contact_form[nom]" required/>
                  </div>
                  <div>
                      <label for="contact_form_prenom">Prenom</label>
                      <input type="text" id="contact_form_prenom" name="contact_form[prenom]" />
                  </div>
                  <!-- add fields as needed -->
                  <input type="hidden" name="contact_form[_token]" data-controller="csrf-protection" value="csrf-token">

                  <button class="btn">Save</button>
                </form>
              <!-- add to script -->
                <script>
                    $(function(){
                        $('form').ajaxForm();
                    });
                </script>
        * -TkT
        */
      //function start:
        //form submit handler
        $(this).on('submit', async function(e) {
          e.preventDefault(); // prevent native submit
          var formData = new FormData(this);

          await fetch(this.action, {
            method: this.method,
            body: formData
          })
          .then((response) => {
            if(response.status === 201 || response.status === 202){
              alert("Formulaire envoyé avec succès.");
              location.reload(); //reload page
            }
            else{
              alert("Erreur lors de l'envoi du formulaire.");
            }
          })
          .catch((error) => console.error(error));
        });

        
        //form population handler for edit forms
        if(this[0].action.match(/\/(\d+)$/)){ //check if action url ends with an id
          var data = urlToJsonObj(this[0].action); //get object data from api
          data.then((result) => {
            for(var key of this[0]){  //itterate form elements
              var m;
              if(m = key.name.match(/\[(.+)\]/)){
                if(result[m[1]]){ //check if key exists in object
                  if(result[m[1]].id){
                    key.value = result[m[1]].id; //set form element value for related objects
                  }
                  else{
                    key.value = result[m[1]]; //set form element value
                  }
                }
              }
            }
          })
          .catch((error) => console.error(error));
        }

    },

});