const upload_btn = document.querySelector(".choose-files");
const upload_input = document.querySelector("#files");
const drag_upload = document.querySelector(".drag-upload");
const drag_info = document.querySelector(".drag-upload p");
const drop_border = document.querySelector(".drop-border");
const upload_form = document.querySelector("[data-repo]");
const upload_msg = document.querySelector("#message");
const submit_btn = document.querySelector(".commit-upload button");

upload_btn.addEventListener("click", () => {
    upload_input.click();
});

drag_upload.addEventListener("dragover", (e) => {
    e.preventDefault();

    drop_border.classList.add("show-border");
    drag_info.textContent = "Drop to upload your files";
});

drag_upload.addEventListener("dragleave", (e) => {
    e.preventDefault();

    drop_border.classList.remove("show-border");
    drag_info.textContent = "Drag additional files here to add them to your repository";
});

drag_upload.addEventListener("drop", (e) => {
    e.preventDefault();

    drop_border.classList.remove("show-border");
    drag_info.textContent = "Drag additional files here to add them to your repository";

    handle_items(e.dataTransfer.items);
});

upload_input.addEventListener("change", () => {
    for (const file of upload_input.files) {
        upload_file(file);
    }
});

const handle_items = (items) => {
    for (const item of items) {
        const entry = item.webkitGetAsEntry();

        if (entry) {
            traverse_item(entry);
        }
    }
}

const traverse_item = (item) => {
    if (item.isFile) {
        item.file((file) => upload_file(file));
    } else if (item.isDirectory) {
        // if (item.name === ".git") return;

        const directoryReader = item.createReader();

        directoryReader.readEntries((entries) => {
            for (let i = 0; i < entries.length; i++) {
                traverse_item(entries[i]);
            }
        });
    }
}

let files = [];

const get_files_size = () => files.map((f) => f.size).reduce((a, c) => a + c, 0);

const file_list = document.querySelector(".files");

const upload_file = (file) => {
    if (files.find((f) => f.name === file.name && f.webkitRelativePath === file.webkitRelativePath)) return;

    if (get_files_size() + file.size > Number(upload_form.dataset.max_size)) return;

    const li = document.createElement("li");

    li.innerHTML = `
        <div>
            <svg aria-label="File" aria-hidden="true" height="16" viewBox="0 0 16 16" version="1.1" width="16" data-view-component="true" >
                <path d="M2 1.75C2 .784 2.784 0 3.75 0h6.586c.464 0 .909.184 1.237.513l2.914 2.914c.329.328.513.773.513 1.237v9.586A1.75 1.75 0 0 1 13.25 16h-9.5A1.75 1.75 0 0 1 2 14.25Zm1.75-.25a.25.25 0 0 0-.25.25v12.5c0 .138.112.25.25.25h9.5a.25.25 0 0 0 .25-.25V6h-2.75A1.75 1.75 0 0 1 9 4.25V1.5Zm6.75.062V4.25c0 .138.112.25.25.25h2.688l-.011-.013-2.914-2.914-.013-.011Z"></path>
            </svg>
        </div>

        <span>${file.webkitRelativePath || file.name}</span>
    `; 

    const remove_btn = document.createElement("div");

    remove_btn.dataset.file = file.webkitRelativePath || file.name;
    remove_btn.innerHTML = `
        <svg aria-hidden="true" height="16" viewBox="0 0 16 16" version="1.1" width="16" data-view-component="true">
            <path d="M3.72 3.72a.75.75 0 0 1 1.06 0L8 6.94l3.22-3.22a.749.749 0 0 1 1.275.326.749.749 0 0 1-.215.734L9.06 8l3.22 3.22a.749.749 0 0 1-.326 1.275.749.749 0 0 1-.734-.215L8 9.06l-3.22 3.22a.751.751 0 0 1-1.042-.018.751.751 0 0 1-.018-1.042L6.94 8 3.72 4.78a.75.75 0 0 1 0-1.06Z"></path>
        </svg>
    `;

    remove_btn.classList.add("remove-btn");

    li.appendChild(remove_btn);

    remove_btn.addEventListener("click", () => {
        files = files.filter((f) => (f.webkitRelativePath || f.name) !== remove_btn.dataset.file);
        li.remove();
    });

    file_list.appendChild(li);

    files.push(file);
}

upload_form.addEventListener("submit", async (e) => {
    if (files.length < 1) return;

    submit_btn.disabled = true;
    submit_btn.textContent = "Uploading...";

    e.preventDefault();

    const form_data = new FormData();

    for (const file of files) {
        form_data.append("files[]", file);
    }

    form_data.append("message", upload_msg.value);

    const res = await fetch(`/repo/upload/index.php?name=${upload_form.dataset.repo}`, {
        method: "POST",
        body: form_data,
        credentials: "include",
    });

    if (res.redirected) document.location.href = `/repo?name=${upload_form.dataset.repo}`
});
