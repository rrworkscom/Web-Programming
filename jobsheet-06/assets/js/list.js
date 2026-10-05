async function loadList(jsonFile, keys) {
    const tbody = document.querySelector(".table-responsive table tbody");
    const loading = document.getElementById("loading-indicator");

    if (!tbody) return;

    loading.style.display = "block";
    tbody.innerHTML = "";

    try {
        await new Promise((resolve) => setTimeout(resolve, 3000));

        const res = await fetch("../data/" + jsonFile);

        if (!res.ok) {
            throw new Error("Failed to fetch data (status " + res.status + ")");
        }

        const list = await res.json();

        list.forEach(function (item) {
            const tr = document.createElement("tr");

            let html = "";

            keys.forEach(function (key) {
                html += "<td>" + item[key] + "</td>";
            });

            html +=
                "<td>" +
                "<button type=\"button\">Edit</button> " +
                "<button type=\"button\" class=\"btn-delete\">Delete</button>" +
                "</td>";

            tr.innerHTML = html;
            tbody.appendChild(tr);
        });

    } catch (err) {
        tbody.innerHTML =
            "<tr><td colspan=\"" + (keys.length + 1) + "\">" +
            "Failed to load data: " + err.message +
            "</td></tr>";

    } finally {
        loading.style.display = "none";
    }
}