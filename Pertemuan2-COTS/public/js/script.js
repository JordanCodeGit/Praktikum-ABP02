$(document).ready(function () {
  // ============================================================
  // LOCAL STATE & UTILITIES
  // ============================================================
  let dataTable = null;
  let currentEditId = null;

  function escapeHtml(text) {
    if (!text) return '';
    return text.toString()
      .replace(/&/g, "&amp;")
      .replace(/</g, "&lt;")
      .replace(/>/g, "&gt;")
      .replace(/"/g, "&quot;")
      .replace(/'/g, "&#039;");
  }

  function hpClass(hp) {
    if (hp >= 80) return '';
    if (hp >= 50) return 'mid';
    return 'low';
  }

  function renderHpBar(hp) {
    const maxHp = 150;
    const pct = Math.min(100, Math.round((hp / maxHp) * 100));
    const cls = hpClass(hp);
    return `
      <div class="hp-bar-wrap">
        <div class="hp-bar">
          <div class="hp-fill ${cls}" style="width:${pct}%"></div>
        </div>
        <span class="hp-text">${escapeHtml(hp)}</span>
      </div>
    `;
  }

  function renderTypeBadge(type) {
    const safeType = escapeHtml(type);
    return `<span class="type-badge type-${safeType.toLowerCase()}">${safeType}</span>`;
  }

  function showAlert(message, type) {
    const $alert = $('#alertBox');
    $alert.removeClass('alert-success alert-danger')
      .addClass('alert-' + type)
      .html(message)
      .fadeIn(200);

    setTimeout(function () { $alert.fadeOut(300); }, 3500);
  }

  function showToast(message, type) {
    const $toast = $('#toastNotif');
    const color = type === 'success' ? '#4caf50' : '#e3350d';
    $toast.find('.toast-header').css('background-color', color);
    $('#toastBody').html(message);
    const toast = new bootstrap.Toast($toast[0], { delay: 3500 });
    toast.show();
  }

  // ============================================================
  // BAGIAN 1: FORM PAGE LOGIC
  // ============================================================
  if ($('#formTambah').length) {
    
    $('#formTambah').on('submit', function (e) {
      e.preventDefault();

      const data = {
        name: $('#name').val().trim(),
        type: $('#type').val(),
        ability: $('#ability').val().trim(),
        hp: $('#hp').val().trim(),
        region: $('#region').val()
      };

      if (!data.name || !data.type || !data.ability || !data.hp || !data.region) {
        showAlert('⚠️ Semua field wajib diisi!', 'danger');
        return;
      }

      if (isNaN(data.hp) || parseInt(data.hp) <= 0) {
        showAlert('⚠️ HP harus berupa angka positif!', 'danger');
        return;
      }

      $.ajax({
        url: '/api/pokemon',
        type: 'POST',
        contentType: 'application/json',
        data: JSON.stringify(data),
        success: function (response) {
          showAlert('✅ ' + escapeHtml(response.pokemon.name) + ' berhasil ditambahkan!', 'success');
          $('#formTambah')[0].reset();
        },
        error: function () {
          showAlert('❌ Terjadi kesalahan pada server.', 'danger');
        }
      });
    });

    $('#btnReset').on('click', function () {
      $('#formTambah')[0].reset();
      $('#alertBox').hide();
    });
  }

  // ============================================================
  // BAGIAN 2: TABLE PAGE LOGIC
  // ============================================================
  if ($('#tabelData').length) {
    
    // 1. Inisialisasi DataTable
    dataTable = $('#tabelData').DataTable({
      ajax: {
        url: '/api/pokemon', 
        dataSrc: ''
      },
      columns: [
        {
          data: 'id',
          orderable: true, 
          render: function (data) {
            return '<span class="badge-no">' + escapeHtml(data) + '</span>';
          }
        },
        { 
          data: 'name',
          render: function (data) { return '<strong>' + escapeHtml(data) + '</strong>'; }
        },
        { 
          data: 'type',
          render: function (data) { return renderTypeBadge(data); }
        },
        { 
          data: 'ability',
          render: function(data) { return escapeHtml(data); }
        },
        { 
          data: 'hp',
          render: function (data) { return renderHpBar(data); }
        },
        { 
          data: 'region',
          render: function(data) { return escapeHtml(data); }
        },
        {
          data: null,
          orderable: false,
          render: function (data, type, row) {
            return `
              <button class="btn btn-warning btn-sm me-1 btn-edit" data-id="${row.id}">
                ✏️ Edit
              </button>
              <button class="btn btn-danger btn-sm btn-delete" data-id="${row.id}" data-name="${escapeHtml(row.name)}">
                🗑️ Hapus
              </button>
            `;
          }
        }
      ],
      language: {
        search: 'Cari Pokémon:',
        lengthMenu: 'Tampilkan _MENU_ Pokémon',
        info: 'Menampilkan _START_–_END_ dari _TOTAL_ Pokémon',
        infoEmpty: 'Tidak ada Pokémon tersedia',
        paginate: { first: '«', last: '»', next: 'Berikut ›', previous: '‹ Sebelum' },
        emptyTable: 'Pokédex masih kosong!',
        zeroRecords: 'Pokémon tidak ditemukan'
      },
      responsive: true,
      pageLength: 5,
      lengthMenu: [5, 10, 25],
      order: [[0, 'asc']]
    });

    // 2. Event Delegation: Edit Button
    $('#tabelData tbody').on('click', '.btn-edit', function () {
      const $row = $(this).closest('tr');
      const rowData = dataTable.row($row).data();

      if (rowData) {
        currentEditId = rowData.id;
        $('#editName').val(rowData.name);
        $('#editType').val(rowData.type);
        $('#editAbility').val(rowData.ability);
        $('#editHp').val(rowData.hp);
        $('#editRegion').val(rowData.region);
        $('#modalEdit').modal('show');
      }
    });

    // 3. Event Delegation: Delete Button
    $('#tabelData tbody').on('click', '.btn-delete', function () {
      const id = $(this).data('id');
      const name = $(this).data('name');
      
      if (confirm('⚠️ Yakin ingin menghapus ' + name + ' dari Pokédex?')) {
        $.ajax({
          url: '/api/pokemon/' + id,
          type: 'DELETE',
          success: function (response) {
            dataTable.ajax.reload(null, false);
            showToast('🗑️ ' + escapeHtml(name) + ' berhasil dihapus!', 'danger');
          },
          error: function () {
            showToast('❌ Gagal menghapus data.', 'danger');
          }
        });
      }
    });

    // 4. Submit Form Edit
    $('#formEdit').on('submit', function (e) {
      e.preventDefault();

      const data = {
        name: $('#editName').val().trim(),
        type: $('#editType').val(),
        ability: $('#editAbility').val().trim(),
        hp: $('#editHp').val().trim(),
        region: $('#editRegion').val()
      };

      $.ajax({
        url: '/api/pokemon/' + currentEditId,
        type: 'PUT',
        contentType: 'application/json',
        data: JSON.stringify(data),
        success: function (response) {
          $('#modalEdit').modal('hide');
          dataTable.ajax.reload(null, false);
          showToast('✅ ' + escapeHtml(response.pokemon.name) + ' berhasil diperbarui!', 'success');
        },
        error: function () {
          showToast('❌ Gagal memperbarui data.', 'danger');
        }
      });
    });
  }
});