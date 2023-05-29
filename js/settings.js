const modals = document.querySelectorAll(".modal, .dialog");
const dialogs = document.querySelectorAll(".dialog");
const open_btns = document.querySelectorAll(".open-modal-btn");
const open_dialog_btns = document.querySelectorAll(".open-dialog-btn");
const close_btns = document.querySelectorAll(".modal .close-btn");
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

open_dialog_btns.forEach((open) => {
    open.addEventListener("click", () => {
        const dialog = get_modal(open.dataset.modal);

        if (!dialog) return;

        if (dialog.open) return dialog.close();

        dialog.show();
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

document.addEventListener("mousedown", (e) => {
    if (e.target.closest(".open-dialog-btn")) return;

    if (!e.target.closest(".dialog")) dialogs.forEach((dialog) => dialog.close());
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

const picture_input = document.querySelector(".picture-upload-wrap #picture");
const picture_form = document.querySelector(".picture-upload-wrap form");
const picture_upload = document.querySelector(".picture-upload-wrap");

picture_upload.addEventListener("click", () => {
    picture_input.click();
});

picture_input.addEventListener("change", () => {
    picture_form.submit();
});
