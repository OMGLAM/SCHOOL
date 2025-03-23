var fullFormData;
var generalFormData;
var personalFormData;
var guardianFormData;
var personalsNextBtnClicked = false;
var guardiansNextBtnClicked = false;
var editing = false;
var editingTeacherId = "";
var preEditedData;
var postEditedData;
var classTeacherValidated = false;
// page settings
var beginIndex = 0;
var limit = 10;
var counter = 1;

function searchFunction(){
    beginIndex = 0;
    counter = 1;

    showTeachers();
}

document.getElementById("prev-page-btn").addEventListener('click', function () {
    beginIndex -= limit;
    showTeachers();
    counter -= 1;


});
document.getElementById("next-page-btn").addEventListener('click', function () {
    beginIndex += limit;
    showTeachers();
    counter += 1;
});

 

document.addEventListener('DOMContentLoaded', ()=>{
    showTeachers();
});

function showTeachers() {

    document.getElementById("next-page-btn").classList.add('disabled');
    document.getElementById("prev-page-btn").classList.add('disabled');

    var tablebody = document.getElementById("teacher-table-body");
    var name = document.getElementById("search-teacher-name").value;

    fetch('../assets/fetchstaff.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded', // or 'application/json' depending on your needs
        },
        body: 'name=' + encodeURIComponent(name),
    })
        .then(response => response.json())
        .then(data => {
         
         
            document.getElementById("next-page-btn").classList.remove('disabled');
            document.getElementById("prev-page-btn").classList.remove('disabled');
         
           if((data + "") === "No_Record"){
           
                tablebody.innerHTML = "";
                document.getElementById("dataNotAvailable").style.display = 'block';
                document.getElementById("next-page-btn").classList.add('disabled');
                document.getElementById("prev-page-btn").classList.add('disabled');
                
                
           }else{
               document.getElementById("dataNotAvailable").style.display = 'none';
          
            document.getElementById("prev-page-btn").classList.remove('disabled');
            document.getElementById("next-page-btn").classList.remove('disabled');
            document.getElementById("page-number").innerHTML = counter + "";

       
            if ((beginIndex + limit) >= data.length) {

                document.getElementById("next-page-btn").classList.add('disabled');
                document.getElementById("prev-page-btn").classList.remove('disabled');
                // make next btn deactive
            }
            else if (beginIndex <= 0) {
                document.getElementById("prev-page-btn").classList.add('disabled');
                document.getElementById("next-page-btn").classList.remove('disabled');
            }
            else { }

            if(beginIndex == 0){
                document.getElementById("prev-page-btn").classList.add('disabled');
            }
            let teachers = "";
            let flag = 0;
            for (let i = beginIndex; i < data.length; i++) {
                if (flag >= limit) {
                    break;
                }
                teachers += data[i];
                flag += 1;
            }
            tablebody.innerHTML = teachers;
           }

            
        })
        .catch(error => {
            console.error('Error:', error);
        });


}
document.getElementById("go").addEventListener("click", function () {
    event.preventDefault();
    // alert("OMG !");
    let myToast = new bootstrap.Toast(document.getElementById('liveToast'));
    let liveToast = document.getElementById("liveToast");
    
    let formData = {
        nom: document.getElementById("nom").value,
        departement: document.getElementById("departement").value,
        numero: document.getElementById("numero").value,
        email: document.getElementById("email").value
    };


    fetch("../assets/addstaff.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify(formData)
    })
    .then(response => response.json()) // Convertit la réponse en JSON
    .then(data => {
        if (data.indexOf("success") != -1) {
            liveToast.style.backgroundColor = "#BBF7D0";
            liveToast.style.color = 'green';
            document.getElementById('toast-alert-message').innerHTML = "Teacher added successfully";
        } else {
            liveToast.style.backgroundColor = "#FECDD3";
            liveToast.style.color = 'red';
            document.getElementById('toast-alert-message').innerHTML = data;
        }
    })
    .catch(error => {
        console.error("Fetch error:", error);
    });
    
 });




 var teacher_id ;
 function deleteTeacherWithId(id) {

    teacher_id = id;
    $('#delete-confirmation-modal').modal('show');

}
function deleteTeacherWithIdSeted() {
    let myToast = new bootstrap.Toast(document.getElementById('liveToast'));
    let liveToast = document.getElementById("liveToast");

    fetch('../assets/removestaff.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded', // or 'application/json' depending on your needs
        },
        body: 'teacherid=' + encodeURIComponent(teacher_id),
    })
        .then(response => response.text())
        .then(data => {

            if (data.indexOf("success") != -1) {
                liveToast.style.backgroundColor = "#FECDD3";
                liveToast.style.color = 'green';
                document.getElementById('toast-alert-message').innerHTML = "Teacher removed successfully";
            } else {
                liveToast.style.backgroundColor = "#FECDD3";
                liveToast.style.color = 'red';
                document.getElementById('toast-alert-message').innerHTML = data;
            }

            $('#delete-confirmation-modal').modal('hide');
            showTeachers();
            myToast.show();


        })
        .catch(error => {
            console.error('Error:', error);
        });
}


document.getElementById('addTeacherButton').addEventListener('click', function () {
    editing = false;
    // cleanForm();
});


function editTeacher(tid){
    
    editTeacherById(tid);

    $('#addTeacherModal').modal('show');
}

function editTeacherById(tid) {
    cleanForm();
    editing = true;
    editingTeacherId = tid;

    fetch('../assets/fetchstaffinfo.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'id=' + encodeURIComponent(tid),
    })
        .then(response => response.json())
        .then(data => {
            // preEditedData = data;

            document.getElementById("nom").value = data['nom'];
            document.getElementById("departement").value = data['departement'];
            document.getElementById("email").value = data['email'];
            document.getElementById("numero").value = data['numero'];
        })
        .catch(error => console.error('Error:', error));

        
}

document.getElementById("confirm-edit-btn").addEventListener('click', event => {

     

    let myToast = new bootstrap.Toast(document.getElementById('liveToast'));
    let liveToast = document.getElementById("liveToast");
    fetch("../assets/editstaff.php", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(postEditedData),
    })
        .then(response => response.text())
        .then(data => {
           

            if (data.indexOf("success") !== -1) {
                liveToast.style.backgroundColor = "#BBF7D0";
                liveToast.style.color = 'green';
                document.getElementById('toast-alert-message').innerHTML = "Details edited successfully";

                cleanForm();
            }
            else {
                liveToast.style.backgroundColor = "#FECDD3";
                liveToast.style.color = 'red';
                document.getElementById('toast-alert-message').innerHTML = data;
                $("#personalInformationModal").modal("show");
            }

            myToast.show();

        })
        .catch(error => {

            console.error("Error:", error);
        });

    $("#edit-confirmation-modal").modal("hide");
    showTeachers();

}, false);


document.getElementById("confirm-edit-btn").addEventListener('click', event => {

     

    let myToast = new bootstrap.Toast(document.getElementById('liveToast'));
    let liveToast = document.getElementById("liveToast");
    fetch("../assets/editstaff.php", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(postEditedData),
    })
        .then(response => response.text())
        .then(data => {
           

            if (data.indexOf("success") !== -1) {
                liveToast.style.backgroundColor = "#BBF7D0";
                liveToast.style.color = 'green';
                document.getElementById('toast-alert-message').innerHTML = "Details edited successfully";

                cleanForm();
            }
            else {
                liveToast.style.backgroundColor = "#FECDD3";
                liveToast.style.color = 'red';
                document.getElementById('toast-alert-message').innerHTML = data;
                $("#personalInformationModal").modal("show");
            }

            myToast.show();

        })
        .catch(error => {

            console.error("Error:", error);
        });

    $("#edit-confirmation-modal").modal("hide");
    showTeachers();

}, false);


document.getElementById("search-teacher-name").addEventListener("keyup", searchFunction);
document.getElementById("search-teacher-name").addEventListener("search", searchFunction);
document.getElementById("searchTeacherByNameBtn").addEventListener('click', searchFunction);