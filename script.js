$(document).ready(function () {
  let currentPage = 1; // Initialize current page
  const rowsPerPage = 3; // Rows per page

  function loadUsers(searchQuery = '', page = 1) {
    const action = searchQuery ? "search" : "read";
    $.ajax({
      url: "server.php",
      type: "POST",
      data: { action: `${action}`, query: searchQuery, page: page },
      success: function (response) {
        const data = JSON.parse(response);
        const users = data.users;
        const totalPages = data.totalPages;

        let html = "";
        if (users === "No users found") {
          html = "No users found";
          $("#userTable tbody").html(html);
          return;
        }
        
        users.forEach((user) => {
          html += `<tr>
                    <td>${user.id}</td>
                    <td>${user.first_name}</td>
                    <td>${user.last_name}</td>
                    <td>${user.phone_no}</td>
                    <td>${user.email}</td>
                    <td>${user.user_role}</td>
                    <td><button onclick="editUser(${user.id}, '${user.first_name}', '${user.last_name}',${user.phone_no}, '${user.email}','${user.user_role}')">Edit</button></td>
                    <td><button onclick="deleteUser(${user.id})">Delete</button></td>
                </tr>`;
        });
        $("#userTable tbody").html(html);

        // Update pagination controls
        $("#pageInfo").text(`Page ${page} of ${totalPages}`);

        if (page === 1) {
          $("#prevPage").prop("disabled", true);
        } else {
          $("#prevPage").prop("disabled", false);
        }

        if (page === totalPages) {
          $("#nextPage").prop("disabled", true);
        } else {
          $("#nextPage").prop("disabled", false);
        }
      }
    });
  }

  loadUsers();

  // Pagination button click events
  $("#prevPage").on("click", function () {
    if (currentPage > 1) {
      currentPage--;
      loadUsers($("#search").val(), currentPage);
    }
  });

  $("#nextPage").on("click", function () {
    currentPage++;
    loadUsers($("#search").val(), currentPage);
  });

  // Search button click
  $("#searchButton").on("click", function () {
    const searchQuery = $("#search").val();
    currentPage = 1; // Reset to first page on search
    loadUsers(searchQuery, currentPage);
  });

  $(document).on("submit", "#createForm, #updateForm", function (e) {
    e.preventDefault();
    const action = $(this).attr("id") === "createForm" ? "insert" : "update";
    const id=$(this).attr("id") === "createForm" ? undefined : $("#editId").val();;
    $.ajax({
      url: "server.php",
      type: "POST",
      data: $(this).serialize() + `&action=${action}` +`&id=${id}`,
      success: function (response) {
        alert(response);
        if (action === "update") {
          //   setSubmitIds();
        //   formIdChanger();
        createFormIdChange();
        }
        $("#createForm")[0]?.reset();
        loadUsers();
      },
    });
  });

  window.editUser = function (
    id,
    first_name,
    last_name,
    phone_no,
    email,
    user_role
  ) {
    // setUpdateIds();
    updateFormIdChanger();
    $("#editId").val(id);
    $("#first_name").val(first_name);
    $("#last_name").val(last_name);
    $("#phone_no").val(phone_no);
    $("#roles").val(user_role);
    $("#email").val(email);
  };


  window.deleteUser = function (id) {
    if (confirm("Are you sure?")) {
      $.ajax({
        url: "server.php",
        type: "POST",
        data: { action: "delete", id: id },
        success: function (response) {
          alert(response);
          loadUsers();
        },
      });
    }
  };

  function updateFormIdChanger() {
    const $formCreateId = $("#createForm");
    const $btnCreate = $("#create");
    const $btnUpdate = $("#update");

    if ($formCreateId.length) {
        $formCreateId.attr("id", "updateForm");
        $btnCreate.hide();
        $btnUpdate.show();
    }
}

function createFormIdChange() {
    const $btnCreate = $("#create");
    const $btnUpdate = $("#update");
    const $formUpdateId = $("#updateForm");

    if ($formUpdateId.length) {
        $formUpdateId.attr("id", "createForm");
        $btnCreate.show();
        $btnUpdate.hide();
    }
}


});
