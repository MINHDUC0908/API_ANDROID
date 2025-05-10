const deleteRatingButtons = document.querySelectorAll(".deleteRatingButton");
deleteRatingButtons.forEach(button => {
    button.addEventListener("click", () => {
        deleteRating(button);
    });
});


async function deleteRating(button) {
    const deleteId = button.getAttribute("data-id");
    const url = `/rating/destroy/${deleteId}`;
    console.log("Delete URL:", url);  // Kiểm tra URL

    try {
        const res = await fetch(url, {
            method: "DELETE",
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
            }
        });

        const data = await res.json();
        console.log("Response:", data);  // Kiểm tra response

        if (data.success) {
            document.getElementById(`rating-row-${deleteId}`).remove();
            let alertContainer = document.createElement('div');
            alertContainer.innerHTML = `
                <div class="alert custom-alert alert-success alert-dismissible fade show border-0 shadow" role="alert">
                    <div class="d-flex align-items-center">
                        <div class="alert-icon-container me-3">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div>
                            <h5 class="alert-heading mb-1">Thành công!</h5>
                            <p class="mb-0">Xóa thành công</p>
                        </div>
                    </div>
                    <div class="progress mt-2" style="height: 3px;">
                        <div class="progress-bar bg-white" style="width: 100%; transition: width 3s linear;"></div>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            `;
            document.body.appendChild(alertContainer); // Thêm vào DOM
            // Ẩn thông báo sau 3 giây
            setTimeout(() => {
                alertContainer.remove();
            }, 3000);
            const deleteModal = bootstrap.Modal.getInstance(document.getElementById("deleteRatingModal-" + deleteId));
            deleteModal.hide();
        } else {
            console.error('Xóa đánh giá thất bại:', data.message);
        }
    } catch (error) {
        console.error('Lỗi khi xóa đánh giá:', error);
    }
}



const ratingTableBody = document.getElementById("ratingTableBody");
const btnFilter = document.getElementById("btnFilter");

// Hàm lọc hình ảnh và số sao cùng lúc
function filterRatingsAndImages() {
    const filterSelect = document.getElementById("filterSelect").value;
    const selectedRating = document.getElementById("ratingFilter").value;

    // Tạo URL động dựa trên giá trị của bộ lọc
    let url = "ratingImage";  // Mặc định lấy tất cả nếu không có filter

    if (filterSelect !== "default" || selectedRating !== "default") {
        url += "/?";
        if (filterSelect !== "default") {
            url += `filter=${filterSelect}`;
        }
        if (selectedRating !== "default") {
            url += `${filterSelect !== "default" ? "&" : ""}rating=${selectedRating}`;
        }
    }

    fetch(url)
        .then(response => response.json())
        .then(data => {
            console.log(data.data);
            ratingTableBody.innerHTML = "";
            data.data.forEach((rating, index) => {
                const row = `
                    <tr class="${index % 2 === 0 ? 'bg-white' : 'bg-light-subtle'}" id="rating-row-${rating.id}">
                        <td class="ps-4">
                            <div class="d-flex align-items-center">
                                <div class="position-relative">
                                    <img src="/imgCustomer/${rating.customer.image || 'default-avatar.png'}" 
                                        alt="Avatar" 
                                        class="rounded-circle border border-primary shadow-sm"
                                        style="width: 45px; height: 45px; object-fit: cover;">
                                        <span class="position-absolute bottom-0 end-0 bg-success rounded-circle p-1 border border-white" 
                                            style="width: 12px; height: 12px;"></span>
                            </div>
                                <div class="ms-3">
                                    <h6 class="mb-0 fw-semibold">${rating.customer.name}</h6>
                                    <small class="text-muted">ID: #${rating.customer.id}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="/imgProduct/${rating.product.images || 'default-product.png'}" 
                                    alt="Product"
                                    class="rounded shadow-sm border"
                                    style="width: 40px; height: 40px; object-fit: cover;">
                                <div class="ms-3">
                                    <h6 class="mb-0 text-truncate" style="max-width: 150px;">${rating.product.product_name}</h6>
                                    <small class="text-muted">SKU: ${rating.product.sku || 'N/A'}</small>
                                </div>
                            </div>
                        </td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center">
                                ${Array.from({ length: 5 }).map((_, i) => 
                                    `<i class="bi ${i < rating.rating ? 'bi-star-fill text-warning' : 'bi-star text-secondary'} mx-1"></i>`
                                ).join('')}
                            </div>
                            <span class="badge bg-${rating.rating >= 4 ? 'success' : (rating.rating >= 3 ? 'warning' : 'danger')} mt-1">
                                ${rating.rating}/5
                            </span>
                        </td>
                        <td class="text-center">
                            ${rating.image 
                                ? `<a href="#" data-bs-toggle="modal" data-bs-target="#imageModal${rating.id}">
                                        <img src="/rating/${rating.image}" 
                                            alt="Hình ảnh đánh giá" 
                                            class="rounded shadow-sm border"
                                            style="width: 60px; height: 60px; object-fit: cover; transition: transform 0.2s;">
                                    </a>` 
                                : '<span class="badge bg-secondary py-2 px-3"><i class="bi bi-camera-slash me-1"></i> Không có ảnh</span>'
                            }
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="bg-light rounded p-2 me-2" style="width: 36px; height: 36px; display: flex; align-items: center; justify-content: center;">
                                    <i class="bi bi-chat-quote text-primary"></i>
                                </div>
                                <span class="d-inline-block" style="max-width: 200px;" data-bs-toggle="tooltip" data-bs-placement="top" title="${rating.comment || 'Không có bình luận'}">
                                    ${rating.comment && rating.comment.length > 50 
                                        ? `${rating.comment.substring(0, 50)}...` 
                                        : (rating.comment || '')}
                                </span>
                            </div>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-light text-dark mb-1">
                                <i class="bi bi-calendar-event me-1"></i>
                                ${new Date(rating.created_at).toLocaleDateString()}
                            </span>
                            <small class="text-muted">
                                <i class="bi bi-clock me-1"></i>
                                ${new Date(rating.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}
                            </small>
                        </td>
                        <td class="text-center">
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#viewModal${rating.id}">
                                    <i class="bi bi-eye me-1"></i> Xem
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-danger"
                                    data-bs-toggle="modal"
                                    data-bs-target="#deleteRatingModal-${rating.id}">
                                    <i class="bi bi-trash me-1"></i> Xóa
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
                ratingTableBody.innerHTML += row;
            });
        });
}

// Gắn sự kiện click vào nút lọc
btnFilter.addEventListener("click", filterRatingsAndImages);


function filterTable() {
    let input = document.querySelector("input[type='text']").value.toLowerCase();  // Lấy giá trị của ô tìm kiếm và chuyển thành chữ thường
    let rows = document.querySelectorAll("#ratingTable tbody tr");

    rows.forEach(row => {
        let name = row.cells[0].textContent.toLowerCase();     // Cột "Người Dùng"
        let product = row.cells[1].textContent.toLowerCase();  // Cột "Sản Phẩm"
        
        if (name.includes(input) || product.includes(input)) {  // Kiểm tra cả tên và sản phẩm
            row.style.display = "";
        } else {
            row.style.display = "none";
        }
    });
}
