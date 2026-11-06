$.ajax({
    url: '/add-to-cart',
    type: 'POST',
    data: {
        product_id: productId,
        quantity: 1,
        _token: $('meta[name="csrf-token"]').attr('content')
    },
    success: function(response) {
        // Tangani sukses
    },
    error: function(xhr) {
        alert(xhr.responseJSON.error);
    }
});

let itemIdToDelete;

function removeItem(itemId) {
    itemIdToDelete = itemId; // Simpan ID item yang akan dihapus
    $('#confirmDeleteModal').modal('show'); // Tampilkan modal
}

$('#confirmDeleteButton').click(function() {
    $.ajax({
        url: '/cart/remove/' + itemIdToDelete,
        type: 'DELETE',
        success: function(response) {
            if (response.success) {
                alert(response.message); // Tampilkan pesan sukses
                $('#item-' + itemIdToDelete).remove(); // Hapus item dari tampilan
                $('#confirmDeleteModal').modal('hide'); // Sembunyikan modal
            }
        },
        error: function(xhr) {
            alert('Gagal menghapus item dari keranjang.');
        }
    });
});