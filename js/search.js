const input = document.querySelector("#search");
const select = document.querySelector(".s-types");
const t_user = document.querySelector("#t-user");
const t_all = document.querySelector("#t-all");
const r_user = document.querySelector("#r-user");
const r_all = document.querySelector("#r-all");
const form = document.querySelector("#s-form");
const t_all_text = document.querySelector("#t-all .s-type-text");
const t_user_text = document.querySelector("#t-user .s-type-text");

const show_select = (e) => {
    t_all_text.textContent = e.target.value;

    if (t_user) t_user_text.textContent = e.target.value;

    if (e.target.value.length > 0) {
        select.hidden = false;
    } else {
        select.hidden = true;
    }
}

input.addEventListener("keyup", show_select);
input.addEventListener("focus", show_select);

document.addEventListener("click", (e) => {
    if (e.target.closest("#t-all")) {
        r_all.checked = true;

        form.submit();
    } else if (e.target.closest("#t-user")) {
        r_user.checked = true;

        form.submit();
    } else if (!e.target.closest("#search")) {
        select.hidden = true;
    }
});

document.querySelectorAll(".s-type").forEach((s_type) => {
    s_type.addEventListener("mouseover", (e) => {
        if (e.target.closest("#t-all")) {
            t_all.setAttribute("active", true);
            t_user.setAttribute("active", false);

            r_all.checked = true;
        } else if (e.target.closest("#t-user")) {
            t_user.setAttribute("active", true);
            t_all.setAttribute("active", false);

            r_user.checked = true;
        }
    });
});