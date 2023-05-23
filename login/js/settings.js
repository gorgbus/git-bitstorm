const modals = document.querySelectorAll(".modal");
const open_btns = document.querySelectorAll(".open-modal-btn");
const close_btns = document.querySelectorAll(".modal-head .close-btn");
const confirm_btns = document.querySelectorAll(".modal .confirm-btn");
const inputs = document.querySelectorAll(".modal input");

const get_modal = (modal_name) => {
    for (const modal of modals) {
        if (modal.dataset.modal === modal_name) return modal;
    }

    return undefined;
}

open_btns.forEach((open) => {
    open.addEventListener("click", () => {
        const modal = get_modal(open.dataset.modal);

        if (!modal) return;

        modal.showModal();
    });
});

close_btns.forEach((close) => {
    close.addEventListener("click", () => {
        const modal = get_modal(close.dataset.modal);

        if (!modal) return;

        modal.close();
    });
});

modals.forEach((modal) => {
    modal.addEventListener("click", (e) => {
        const dimensions = modal.getBoundingClientRect();

        if (
            e.clientX < dimensions.left ||
            e.clientX > dimensions.right ||
            e.clientY < dimensions.top ||
            e.clientY > dimensions.bottom
        ) {
            modal.close();
        }
    });
});

const get_confirm_btn = (modal_name) => {
    for (const confirm of confirm_btns) {
        if (confirm.dataset.modal === modal_name) return confirm;
    }

    return undefined;
}

inputs.forEach((input) => {
    input.addEventListener("keyup", () => {
        const confirm = get_confirm_btn(input.dataset.modal);

        if (!confirm) return;

        if (input.dataset.confirm === input.value) confirm.disabled = false;
        else confirm.disabled = true;
    });
});
