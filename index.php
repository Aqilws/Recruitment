<?php
require 'db.php';
$query = "SELECT * FROM data_reqruitment";
$result = $conn->query($query);

// test ik
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Recruitment Management System</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>
  <style>
    .status-successful {
      border-left: 4px solid #10b981;
    }

    .status-on-progress {
      border-left: 4px solid #10b981;
    }

    .status-cancel {
      border-left: 4px solid #ef4444;
    }

    .status-no-respon {
      border-left: 4px solid #f59e0b;
    }

    .status-blacklist {
      border-left: 4px solid #64748b;
    }

    .drag-active {
      border-color: #3b82f6;
      background-color: #f0f9ff;
    }
  </style>
</head>

<body class="bg-gray-50">
  <div class="container mx-auto px-4 py-8">
    <header class="mb-8 text-center flex flex-col justify-center items-center">
      <img src="assets/mia-logo.png" width="120" alt="">
      <h1 class="text-3xl font-bold text-gray-800 uppercase">
        Recruitment Management System
      </h1>
      <p class="text-gray-600">PT. MITRA INTI ANTAR BANGSA</p>
    </header>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <!-- Import Section -->
      <div class="bg-white rounded-lg shadow p-6 lg:col-span-4">
        <h2 class="text-xl font-semibold mb-4 text-gray-800 uppercase">
          Import Candidates
        </h2>

        <div
          id="drop-area"
          class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center cursor-pointer transition-colors duration-200 mb-4">
          <i class="fas fa-file-excel text-4xl text-green-600 mb-3"></i>
          <p class="text-gray-600 mb-2 uppercase">Drag & drop Excel file here</p>
          <p class="text-sm text-gray-500">or</p>
          <?php if (isset($_GET['success'])): ?>
            <p class="success uppercase">Import berhasil!</p>
          <?php endif; ?>
          <form action="import.php" method="post" enctype="multipart/form-data">
            <input
              type="file"
              id="fileInput"
              accept=".xlsx, .xls"
              class="hidden"
              name="excel_file" />
            <button
              id="browseBtn"
              class="uppercase mt-2 px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors duration-200">
              Browse Files
            </button>

          </form>

        </div>

      </div>

      <!-- Stats Section -->
      <div class="bg-white rounded-lg shadow p-6 lg:col-span-4">
        <h2 class="text-xl font-semibold mb-4 text-gray-800 uppercase">
          Recruitment Overview
        </h2>
        <div class="flex justify-center items-center gap-2 my-4">

          <?php
          // Total data
          $total = $conn->query("SELECT COUNT(*) as total FROM data_reqruitment")->fetch_assoc()['total'];

          // Total status Baru
          $totalBaru = $conn->query("SELECT COUNT(*) as total FROM data_reqruitment WHERE status = 'Baru'")->fetch_assoc()['total'];
          ?>


          <div class="bg-blue-500 p-4 rounded-lg w-1/6 h-28 text-center border-2 border-solid border-blue-900">

            <div class="text-center">
              <div>
                <p class="text-2xl font-medium text-gray-50 uppercase">Data Total</p>
                <p
                  id=""
                  class="text-4xl font-bold text-gray-50">
                  <?= $total ?>
                </p>
              </div>
            </div>
          </div>
          <div class="bg-blue-500 p-4 rounded-lg w-1/6 h-28 text-center border-2 border-solid border-blue-900">
            <div class="text-center">
              <div>
                <p class="text-2xl font-medium text-gray-50 uppercase">Fresh Data</p>
                <p
                  id=""
                  class="text-4xl font-bold text-gray-50">
                  <?= $totalBaru ?>
                </p>
              </div>
            </div>
          </div>
        </div>


        <div
          class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
          <div class="bg-green-50 p-4 rounded-lg border-2 border-solid border-green-600">

            <div class="flex items-center justify-between">
              <div>
                <p class="text-md font-medium text-green-800 uppercase ">Successful</p>
                <p
                  id="successful-count"
                  class="text-2xl font-bold text-green-600">
                  0
                </p>
              </div>
              <i class="fas fa-check-circle text-green-400 text-2xl"></i>
            </div>
          </div>
          <div class="bg-blue-50 p-4 rounded-lg border-2 border-solid border-blue-600">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-md font-medium text-blue-800 uppercase">On Progress</p>
                <p
                  id="on-progress-count"
                  class="text-2xl font-bold text-blue-600">
                  0
                </p>
              </div>
              <i class="fa-regular fa-clock text-blue-400 text-2xl"></i>

            </div>
          </div>



          <div class="bg-red-50 p-4 rounded-lg border-2 border-solid border-red-600">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-md font-medium text-red-800 uppercase">Canceled</p>
                <p id="cancel-count" class="text-2xl font-bold text-red-600">
                  0
                </p>
              </div>
              <i class="fas fa-times-circle text-red-400 text-2xl"></i>
            </div>
          </div>

          <div class="bg-yellow-50 p-4 rounded-lg border-2 border-solid border-yellow-600">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-md font-medium text-yellow-800 uppercase">No Response</p>
                <p
                  id="no-respon-count"
                  class="text-2xl font-bold text-yellow-600">
                  0
                </p>
              </div>
              <i class="fas fa-comment-slash text-yellow-400 text-2xl"></i>
            </div>
          </div>

          <div class="bg-gray-50 p-4 rounded-lg border-2 border-solid border-gray-600">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-md font-medium text-gray-800 uppercase">Blacklisted</p>
                <p
                  id="blacklist-count"
                  class="text-2xl font-bold text-gray-600">
                  0
                </p>
              </div>
              <i class="fas fa-ban text-gray-400 text-2xl"></i>
            </div>
          </div>

        </div>

        <div class="flex flex-wrap items-center justify-between mb-4">
          <h3 class="text-xl font-semibold mb-4 text-gray-800 uppercase">Candidate List</h3>
          <div class="flex space-x-2">
            <form method="POST" action="set_on_progress.php" class="flex items-center gap-2">

              <input type="number" name="jumlah" id="jumlah" min="1" required class="border border-blue-600 rounded-md px-2 py-1 w-28">
              <button type="submit" class="bg-blue-600 text-white px-3 py-2 rounded uppercase">Proses</button>
            </form>

            <select id="monthFilter" class="uppercase text-md px-3 py-2 bg-blue-600 text-white  rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
              <option value="">All Months</option>
              <option value="01">January</option>
              <option value="02">February</option>
              <option value="03">March</option>
              <option value="04">April</option>
              <option value="05">May</option>
              <option value="06">June</option>
              <option value="07">July</option>
              <option value="08">August</option>
              <option value="09">September</option>
              <option value="10">October</option>
              <option value="11">November</option>
              <option value="12">December</option>
            </select>

            <input type="number" id="yearFilter" value="2025" style="display: none;" class="border rounded px-2 py-1 w-28" placeholder="e.g. 2025" min="2000" max="2099" />

            <select
              id="status-filter"
              class="px-3 py-2 bg-blue-600 text-white uppercase text-md rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
              <option value="all">All Statuses</option>
              <option value="successful">Successful</option>
              <option value="on-progress">On Progress</option>
              <option value="cancel">Canceled</option>
              <option value="no-respon">No Response</option>
              <option value="blacklist">Blacklisted</option>
            </select>
            <button
              id="export-btn"
              class="px-3 py-2 bg-blue-600 text-white uppercase text-md rounded-md text-sm hover:bg-blue-700 transition-colors duration-200 flex items-center">
              <i class="fas fa-file-export mr-2"></i> Export
            </button>
          </div>
        </div>

        <div class="overflow-x-auto">

          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Status Date
                <th
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Name
                </th>
                <th
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Age
                </th>
                <th
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Experience
                </th>
                <th
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Grade
                </th>
                <th
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Status
                </th>
                <th
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Actions
                </th>
              </tr>
            </thead>
            <tbody
              id="candidate-table"
              class="bg-white divide-y divide-gray-200">
              <!-- Data will be inserted here by JavaScript -->
              <tr>
                <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                  No candidate data available. Import an Excel file to get
                  started.
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- Status Update Modal -->
  <div
    id="status-modal"
    class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md">
      <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-medium text-gray-900">
          Update Candidate Status
        </h3>
        <button id="close-modal" class="text-gray-400 hover:text-gray-500">
          <i class="fas fa-times"></i>
        </button>
      </div>
      <div class="mb-4">
        <p class="text-sm text-gray-500">
          Updating status for:
          <span
            id="modal-candidate-name"
            class="font-medium text-gray-700"></span>
        </p>
      </div>
      <div class="mb-6">
        <label
          for="status-select"
          class="block text-sm font-medium text-gray-700 mb-2">Select Status</label>
        <select
          id="status-select"
          class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
          <option value="successful">Successful</option>
          <option value="on-progress">On Progress</option>
          <option value="cancel">Canceled</option>
          <option value="no-respon">No Response</option>
          <option value="blacklist">Blacklisted</option>
        </select>
      </div>
      <div class="flex justify-end space-x-3">
        <button
          id="cancel-update"
          class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
          Cancel
        </button>
        <button
          id="confirm-update"
          class="px-4 py-2 bg-blue-600 text-white rounded-md text-sm font-medium hover:bg-blue-700">
          Update Status
        </button>
      </div>
    </div>
  </div>

  <script>
    // Sample data storage (in a real app, this would be server-side)
    let candidates = [];
    let currentCandidateId = null;

    // DOM Elements
    const dropArea = document.getElementById("drop-area");
    const fileInput = document.getElementById("fileInput");
    const browseBtn = document.getElementById("browseBtn");
    const candidateTable = document.getElementById("candidate-table");
    const statusFilter = document.getElementById("status-filter");
    const exportBtn = document.getElementById("export-btn");
    const statusModal = document.getElementById("status-modal");
    const closeModal = document.getElementById("close-modal");
    const cancelUpdate = document.getElementById("cancel-update");
    const confirmUpdate = document.getElementById("confirm-update");
    const statusSelect = document.getElementById("status-select");
    const modalCandidateName = document.getElementById(
      "modal-candidate-name"
    );

    // Count elements
    const successfulCount = document.getElementById("successful-count");
    const cancelCount = document.getElementById("cancel-count");
    const onProgressCount = document.getElementById("on-progress-count");
    const noResponCount = document.getElementById("no-respon-count");
    const blacklistCount = document.getElementById("blacklist-count");
    document.getElementById("monthFilter").addEventListener("change", renderCandidateTable);
    document.getElementById("yearFilter").addEventListener("input", renderCandidateTable);


    // Event Listeners
    browseBtn.addEventListener("click", () => fileInput.click());
    fileInput.addEventListener("change", handleFileSelect);

    // Drag and drop events
    ["dragenter", "dragover", "dragleave", "drop"].forEach((eventName) => {
      dropArea.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
      e.preventDefault();
      e.stopPropagation();
    }

    ["dragenter", "dragover"].forEach((eventName) => {
      dropArea.addEventListener(eventName, highlight, false);
    });

    ["dragleave", "drop"].forEach((eventName) => {
      dropArea.addEventListener(eventName, unhighlight, false);
    });

    function highlight() {
      dropArea.classList.add("drag-active");
    }

    function unhighlight() {
      dropArea.classList.remove("drag-active");
    }

    dropArea.addEventListener("drop", handleDrop, false);

    function handleDrop(e) {
      const dt = e.dataTransfer;
      const files = dt.files;
      if (files.length) {
        fileInput.files = files;
        handleFileSelect({
          target: fileInput
        });
      }
    }

    statusFilter.addEventListener("change", renderCandidateTable);
    exportBtn.addEventListener("click", exportData);

    // Modal events
    closeModal.addEventListener("click", () =>
      statusModal.classList.add("hidden")
    );
    cancelUpdate.addEventListener("click", () =>
      statusModal.classList.add("hidden")
    );
    confirmUpdate.addEventListener("click", updateCandidateStatus);

    // Functions
    function handleFileSelect(event) {
      const file = event.target.files[0];
      if (!file) return;

      const reader = new FileReader();
      reader.onload = function(e) {
        const data = new Uint8Array(e.target.result);
        const workbook = XLSX.read(data, {
          type: "array"
        });

        // Assuming first sheet is the one we want
        const firstSheet = workbook.Sheets[workbook.SheetNames[0]];
        const jsonData = XLSX.utils.sheet_to_json(firstSheet);

        // Process the data
        processImportedData(jsonData);
      };
      reader.readAsArrayBuffer(file);
    }

    function processImportedData(data) {
      // Clear existing data (in a real app, you might want to append)
      candidates = [];

      // Process each row
      data.forEach((row, index) => {
        const candidate = {
          id: Date.now() + index, // Simple unique ID
          name: row.name || "",
          age: row.age || "",
          gender: row.gender || "",
          address: row.address || "",
          experience: row.experience || "",
          grade: row.grade || "",
          phone: row.phone || "",
          email: row.email || "",
          status: "no-respon", // Default status
        };

        candidates.push(candidate);
      });

      fetch("save_candidates.php", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify(data),
        })
        .then((response) => response.json())
        .then((result) => {
          if (result.success) {
            alert(
              `Successfully imported ${data.length} candidates to database.`
            );
          } else {
            alert("Failed to save data to database.");
          }
        });

      // Update UI
      updateCounts();
      renderCandidateTable();

      // Show success message (in a real app, you might want a more elegant notification)
      alert(`Successfully imported ${data.length} candidates`);
    }

    function updateCounts() {
      const successful = candidates.filter(
        (c) => c.status === "successful"
      ).length;
      const canceled = candidates.filter((c) => c.status === "cancel").length;
      const onProgress = candidates.filter((c) => c.status === "on-progress").length;
      const noRespon = candidates.filter(
        (c) => c.status === "no-respon"
      ).length;
      const blacklisted = candidates.filter(
        (c) => c.status === "blacklist"
      ).length;

      successfulCount.textContent = successful;
      onProgressCount.textContent = onProgress;
      cancelCount.textContent = canceled;
      noResponCount.textContent = noRespon;
      blacklistCount.textContent = blacklisted;

    }


    function updateProgress(id, boxNumber) {
      const candidateIndex = candidates.findIndex((c) => c.id === id);
      if (candidateIndex === -1) return;

      const current = candidates[candidateIndex];
      let newProgress = current.progress || 0;

      // Toggle logic: klik 1 = aktif, klik ulang = mati
      if (boxNumber === 1) {
        newProgress = newProgress === 1 || newProgress === 2 ? 0 : 1;
      } else if (boxNumber === 2) {
        newProgress = newProgress === 2 ? 1 : 2;
      }

      // Jika sudah dua ceklis, otomatis blacklist
      let newStatus = current.status;
      if (newProgress === 2) newStatus = "blacklist";

      fetch("update_progress.php", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify({
            id,
            progress: newProgress,
            status: newStatus,
          }),
        })
        .then((res) => res.json())
        .then((result) => {
          if (result.success) {
            candidates[candidateIndex].progress = newProgress;
            candidates[candidateIndex].status = newStatus;
            candidates[candidateIndex].status_date = result.status_date; // ← update tanggal

            updateCounts();
            renderCandidateTable();
          } else {
            alert("Failed to update progress");
          }
        })
    }

    function saveComment(id, comment) {
      fetch("save_comment.php", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify({
            id,
            comment
          }),
        })
        .then((res) => res.json())
        .then((data) => {
          if (data.success) {
            alert("Comment saved!");
            const candidate = candidates.find((c) => c.id === id);
            if (candidate) candidate.comment = comment;
          } else {
            alert("Failed to save comment");
          }
        })
        .catch((err) => {
          console.error("Error saving comment:", err);
          alert("Server error while saving comment");
        });
    }



    function renderCandidateTable() {
      const statusVal = statusFilter.value;
      const monthVal = document.getElementById("monthFilter").value;
      const yearVal = document.getElementById("yearFilter").value;

      let filtered = candidates;

      if (statusVal !== "all") {
        filtered = filtered.filter((c) => c.status === statusVal);
      }

      if (monthVal) {
        filtered = filtered.filter((c) => {
          const date = new Date(c.tanggal);
          return (date.getMonth() + 1).toString().padStart(2, "0") === monthVal;
        });
      }

      if (yearVal) {
        filtered = filtered.filter((c) => {
          const date = new Date(c.tanggal);
          return date.getFullYear().toString() === yearVal;
        });
      }

      if (filtered.length === 0) {
        candidateTable.innerHTML = `
      <tr>
        <td colspan="6" class="text-center text-gray-500 px-6 py-4">No candidates found for selected filters.</td>
      </tr>`;
        return;
      }

      let tableHTML = "";

      filtered.forEach((candidate) => {
        const statusClass = getStatusClass(candidate.status);
        const statusText = getStatusText(candidate.status);

        tableHTML += `
      <tr class="${statusClass}">
      <td class="px-6 py-4">${candidate.status_date ? candidate.status_date : "-"}</td>
        <td class="px-6 py-4">${candidate.name}<br><small>${candidate.email}</small></td>
        <td class="px-6 py-4">${candidate.age}</td>
        <td class="px-6 py-4">${candidate.experience} years</td>
        <td class="px-6 py-4">${candidate.grade}</td>
        <td class="px-6 py-4">
          <span class="inline-block px-2 py-1 rounded ${getStatusBadgeClass(candidate.status)}">${statusText}</span>
        </td>
        <td class="px-6 py-4 text-right">
  ${
        candidate.status === "on-progress"
          ? `<input type="text" placeholder="Comment" 
              class="border px-2 py-1 mt-2 text-sm" 
              onchange="saveComment(${candidate.id}, this.value)" 
              value="${candidate.comment || ''}" />`
          : ''
      }
  ${

    candidate.status === "no-respon"
      ? `
        <p class="text-red-800 bg-red-100 inline-block px-2 py-1 rounded mr-2">${candidate.comment ? candidate.comment : ''}</p>
        <label><input type="checkbox" class="text-blue-600 hover:text-blue-900 mr-2 w-[20px] h-[20px]" onchange="updateProgress(${candidate.id}, 1)" ${candidate.progress >= 1 ? 'checked' : ''} /></label>
        <label><input type="checkbox" class="text-blue-600 hover:text-blue-900 mr-2 w-[20px] h-[20px]" onchange="updateProgress(${candidate.id}, 2)" ${candidate.progress === 2 ? 'checked' : ''} /></label>
      `
      : ""
  }
  <button onclick="openStatusModal(${candidate.id})" class="text-blue-600 hover:text-blue-900 mr-2"><i class="fas fa-edit"></i></button>
  <button onclick="viewCandidateDetails(${candidate.id})" class="text-gray-600 hover:text-gray-900"><i class="fas fa-eye"></i></button>
</td>
      </tr>
    `;
      });

      candidateTable.innerHTML = tableHTML;
    }


    function getStatusClass(status) {
      switch (status) {
        case "successful":
          return "status-successful";
        case "cancel":
          return "status-cancel";
        case "no-respon":
          return "status-no-respon";
        case "blacklist":
          return "status-blacklist";
        case "on-progress":
          return "status-on-progress";
        default:
          return "";
      }
    }

    function getStatusText(status) {
      switch (status) {
        case "successful":
          return "Successful";
        case "on-progress":
          return "On Progress";
        case "cancel":
          return "Canceled";
        case "no-respon":
          return "No Response";
        case "blacklist":
          return "Blacklisted";
        default:
          return "Fresh Data";
      }
    }

    function getStatusBadgeClass(status) {
      switch (status) {
        case "successful":
          return "bg-green-100 text-green-800";
        case "on-progress":
          return "bg-green-100 text-green-800";
        case "cancel":
          return "bg-red-100 text-red-800";
        case "no-respon":
          return "bg-yellow-100 text-yellow-800";
        case "blacklist":
          return "bg-gray-100 text-gray-800";
        default:
          return "bg-blue-100 text-blue-800";
      }
    }

    function openStatusModal(id) {
      currentCandidateId = id;
      const candidate = candidates.find((c) => c.id === id);

      if (candidate) {
        modalCandidateName.textContent = candidate.name;
        statusSelect.value = candidate.status;
        statusModal.classList.remove("hidden");
      }
    }

    function updateCandidateStatus() {
      const newStatus = statusSelect.value;
      const candidateIndex = candidates.findIndex((c) => c.id === currentCandidateId);

      if (candidateIndex !== -1) {
        const candidate = candidates[candidateIndex];

        // Kirim perubahan ke server
        fetch("update_status.php", {
            method: "POST",
            headers: {
              "Content-Type": "application/json",
            },
            body: JSON.stringify({
              id: candidate.id,
              status: newStatus,
              status_date: newStatus === "on-progress" ? new Date().toISOString().slice(0, 19).replace("T", " ") : null,
            }),
          })
          .then((res) => res.json())
          .then((result) => {
            if (result.success) {
              // Update lokal setelah berhasil simpan
              candidates[candidateIndex].status = newStatus;
              updateCounts();
              renderCandidateTable();
              statusModal.classList.add("hidden");
              alert("Status updated successfully");
            } else {
              alert("Failed to update status");
            }
          })
          .catch((err) => {
            console.error("Error updating status:", err);
            alert("Server error");
          });
      }
    }

    function viewCandidateDetails(id) {
      const candidate = candidates.find((c) => c.id === id);

      if (candidate) {
        // In a real app, you might show a detailed modal or navigate to a detail page
        alert(
          `Candidate Details:\n\nName: ${candidate.name}\nAge: ${
              candidate.age
            }\nStatus Date: ${
              candidate.status_date
            }\nGender: ${candidate.gender}\nAddress: ${
              candidate.address
            }\nExperience: ${candidate.experience} years\nGrade: ${
              candidate.grade
            }\nPhone: ${candidate.phone}\nEmail: ${
              candidate.email
            }\nStatus: ${getStatusText(candidate.status)}`
        );
      }
    }

    function exportData() {
      const filterValue = statusFilter.value;
      let dataToExport = candidates;

      if (filterValue !== "all") {
        dataToExport = candidates.filter((c) => c.status === filterValue);
      }

      if (dataToExport.length === 0) {
        alert("No data to export with the current filter");
        return;
      }

      // Convert to worksheet
      const ws = XLSX.utils.json_to_sheet(
        dataToExport.map((c) => ({
          Name: c.name,
          StatusDate: c.status === "on-progress" ? c.status_date : "",
          Age: c.age,
          Gender: c.gender,
          Address: c.address,
          Experience: c.experience,
          Grade: c.grade,
          Phone: c.phone,
          Email: c.email,
          Status: getStatusText(c.status),
        }))
      );

      // Create workbook
      const wb = XLSX.utils.book_new();
      XLSX.utils.book_append_sheet(wb, ws, "Candidates");

      // Export to file
      const fileName = `candidates_${filterValue}_${new Date()
          .toISOString()
          .slice(0, 10)}.xlsx`;
      XLSX.writeFile(wb, fileName);
    }

    // Initialize with some sample data for demo purposes
    function initSampleData() {
      fetch("get_candidates.php")
        .then((response) => response.json())
        .then((data) => {
          candidates = data.map((c, i) => ({
            ...c,
            id: parseInt(c.id), // pastikan ID dalam format integer
            age: parseInt(c.age),
            experience: parseInt(c.experience),
            status_date: c.status_date && c.status_date !== "null" ? c.status_date : "",
            progress: parseInt(c.progress) || 0,
          }));
          updateCounts();
          renderCandidateTable();
        })
        .catch((error) => {
          console.error("Failed to load data:", error);
        });
    }

    // Initialize with sample data
    initSampleData();
  </script>
</body>

</html>
