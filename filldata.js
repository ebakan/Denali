
    function count(obj) {
      var c = 0;
      for (var key in obj) {
        if (obj.hasOwnProperty(key)) ++c;
      }
      return c;
    }

    function getTitles(timeslot)
    {   
        var titles = new Object;
        for(var x = 0; x < count(data[timeslot]); x++)
        {
            var key = data[timeslot][x].id
            titles[key] = data[timeslot][x].title;
        }

        return titles;
    }

    var titles = new Object();
    var ids = new Object();
    var speakers = new Object();
    var descriptions = new Object();
    var timeslots = new Object();
    var lengths = new Object();
    var locations = new Object();
    var capacities = new Object();
    var emails = new Object();
    var counts = new Object();
    
    //fill data
    var i = 0;
    for(var x = 1; x <= count(data); x++)
        {
            for(var y = 0; y < count(data[x]); y++)
                {
                   key = data[x][y].id;
                   titles[key] = data[x][y].title;  
                   ids[key] = data[x][y].id;  
                   speakers[key] = data[x][y].speaker;  
                   descriptions[key] = data[x][y].description;  
                   timeslots[key] = data[x][y].timeslot;  
                   lengths[key] = data[x][y].length;  
                   locations[key] = data[x][y].location;  
                   titles[capacities] = data[x][y].capacity;  
                   emails[key] = data[x][y].email;  
                   counts[key] = data[x][y].count;  
                    
                } 

        }
    
    var firstTimeSlotTitles = getTitles(1);
    var secondTimeSlotTitles = getTitles(2);
    var thirdTimeSlotTitles = getTitles(3);
    var fourthTimeSlotTitles = getTitles(4);
    //for(var x = 0; x < firstTimeSlotTitles.length; x++) document.writeln(firstTimeSlotTitles[x]);


       /* $.each(titles, function(key, value) {
            $('#mySelect').append(
                $('<option></option>').text(value)
            );
        });
*/

        $("#mySelect").change(function () {
            var str = "";
            $("select#mySelect option:selected").each(function () {
            //    str += $(this).attr("id"); /* + " " + $(this).attr('id');*/
                fillData($(this).attr("id"));
                //sayHi();
            });
            //$("#message_display").text(str);
        })
        .trigger('change');

        function fillData(id) { 
           
            if($('#stepDesc0').hasClass('current')){
               $("#id1").html(ids[id]);
               $("#title1").html(titles[id]);
               $("#speaker1").html(speakers[id]);
               $("#description1").html(descriptions[id]);
               $("#timeslot1").html(timeslots[id]);
               $("#length1").html(lengths[id]);
               $("#location1").html(locations[id]);
               $("#capacity1").html(capacities[id]);
               $("#email1").html(emails[id]);
               $("#count1").html(counts[id]);
                 
            } else if($('#stepDesc1').hasClass('current')){
               $("#id2").html(ids[id]);
               $("#title2").html(titles[id]);
               $("#speaker2").html(speakers[id]);
               $("#description2").html(descriptions[id]);
               $("#timeslot2").html(timeslots[id]);
               $("#length2").html(lengths[id]);
               $("#location2").html(locations[id]);
               $("#capacity2").html(capacities[id]);
               $("#email2").html(emails[id]);
               $("#count2").html(counts[id]);
            } else if($('#stepDesc2').hasClass('current')){
               $("#id3").html(ids[id]);
               $("#title3").html(titles[id]);
               $("#speaker3").html(speakers[id]);
               $("#description3").html(descriptions[id]);
               $("#timeslot3").html(timeslots[id]);
               $("#length3").html(lengths[id]);
               $("#location3").html(locations[id]);
               $("#capacity3").html(capacities[id]);
               $("#email3").html(emails[id]);
               $("#count3").html(counts[id]);
            } else if($('#stepDesc3').hasClass('current')){
               $("#id4").html(ids[id]);
               $("#title4").html(titles[id]);
               $("#speaker4").html(speakers[id]);
               $("#description4").html(descriptions[id]);
               $("#timeslot4").html(timeslots[id]);
               $("#length4").html(lengths[id]);
               $("#location4").html(locations[id]);
               $("#capacity4").html(capacities[id]);
               $("#email4").html(emails[id]);
               $("#count4").html(counts[id]);
            }
        }
        


