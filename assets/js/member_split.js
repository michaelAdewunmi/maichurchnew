function member_split()
{
    var mysearchquery = document.getElementById('typeahead').value;
    if(mysearchquery!="")
    {

        //alert(mysearchquery);
    var member_det = document.getElementById('typeahead').value;
    var arr = member_det.split(" ");
        if(arr.length>5) {
            if (arr.length ==8) {

                document.getElementById('memb_id').value = arr[0];
                document.getElementById('memb_band').value = arr[6];
                document.getElementById('memb_name').value = arr[1] + " " + arr[2] + " " + arr[3] + " " + arr[4];
                document.getElementById('memb_branch').value = arr[7];
                document.getElementById('memb_phone').value = arr[5];
            } else if (arr.length ==7) {

                document.getElementById('memb_id').value = arr[0];
                document.getElementById('memb_band').value = arr[5];
                document.getElementById('memb_name').value = arr[1] + " " + arr[2] + " " + arr[3];
                document.getElementById('memb_branch').value = arr[6];
                document.getElementById('memb_phone').value = arr[4];

            } else {
                document.getElementById('memb_id').value = arr[0];
                document.getElementById('memb_band').value = arr[4];
                document.getElementById('memb_name').value = arr[1] + " " + arr[2];
                document.getElementById('memb_branch').value = arr[5];
                document.getElementById('memb_phone').value = arr[3];
            }
        }
    }
}