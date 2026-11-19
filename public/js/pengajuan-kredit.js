document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("formKredit");

    if (!form) {
        return;
    }

    const jenisKredit = document.getElementById("jenisKredit");
    const nominalKredit = document.getElementById("nominal_kredit");
    const statusNikah = document.getElementById("statusNikah");
    const domisili = document.getElementById("domisili");
    const usahaSection = document.getElementById("usahaSection");
    const pekerjaanSection = document.getElementById("pekerjaanSection");
    const keluargaSection = document.getElementById("keluargaSection");
    const alamatDomisiliContainer = document.getElementById("alamatDomisiliContainer");
    const alamatDomisili = document.getElementById("alamatDomisili");
    const popup = document.getElementById("popup");
    const popupText = document.getElementById("popupText");
    const closePopup = document.getElementById("closePopup");

    // Daftar produk diambil dari config Laravel agar tidak perlu diubah di dua tempat
    const kreditUsaha = (form.dataset.produkUsaha || "")
        .split(",")
        .map(function (item) { return item.trim(); })
        .filter(Boolean);

    const kreditPekerjaan = (form.dataset.produkPekerjaan || "")
        .split(",")
        .map(function (item) { return item.trim(); })
        .filter(Boolean);

    let nilaiKeluarga = {};

    try {
        nilaiKeluarga = JSON.parse(form.dataset.nilaiKeluarga || "{}") || {};
    } catch (e) {
        nilaiKeluarga = {};
    }

    function showPopup(message) {
        popupText.textContent = message;
        popup.classList.add("show");
        popup.setAttribute("aria-hidden", "false");
    }

    function hidePopup() {
        popup.classList.remove("show");
        popup.setAttribute("aria-hidden", "true");
    }

    if (closePopup) {
        closePopup.addEventListener("click", hidePopup);
    }

    if (popup) {
        popup.addEventListener("click", function (event) {
            if (event.target === popup) {
                hidePopup();
            }
        });

        document.addEventListener("keydown", function (event) {
            if (event.key === "Escape" && popup.classList.contains("show")) {
                hidePopup();
            }
        });
    }

    function formatNominal() {
        if (!nominalKredit) {
            return;
        }

        const angka = nominalKredit.value.replace(/\D/g, "");
        nominalKredit.value = angka
            ? new Intl.NumberFormat("id-ID").format(Number(angka))
            : "";
    }

    if (nominalKredit) {
        nominalKredit.addEventListener("input", formatNominal);
    }

    function setSectionState(section, show) {
        if (!section) {
            return;
        }

        const fields = section.querySelectorAll("input, select, textarea");

        if (show) {
            section.classList.remove("hidden");
            fields.forEach(function (field) {
                field.disabled = false;
            });
            return;
        }

        section.classList.add("hidden");
        fields.forEach(function (field) {
            field.disabled = true;

            if (field.type === "checkbox") {
                field.checked = false;
            } else {
                field.value = "";
            }
        });
    }

    function terapkanJenisKredit() {
        const value = jenisKredit ? jenisKredit.value : "";

        setSectionState(usahaSection, kreditUsaha.includes(value));
        setSectionState(pekerjaanSection, kreditPekerjaan.includes(value));
    }

    function isiNilaiLama(container) {
        container.querySelectorAll("input, select, textarea").forEach(function (field) {
            if (!field.name || !Object.prototype.hasOwnProperty.call(nilaiKeluarga, field.name)) {
                return;
            }

            field.value = nilaiKeluarga[field.name];
        });
    }

    function renderKeluarga() {
        if (!keluargaSection || !statusNikah) {
            return;
        }

        const value = statusNikah.value;
        const templateId = value === "menikah"
            ? "tplMenikah"
            : value === "belum"
                ? "tplBelum"
                : value === "cerai"
                    ? "tplCerai"
                    : null;

        keluargaSection.innerHTML = "";

        if (!templateId) {
            keluargaSection.classList.add("hidden");
            return;
        }

        const template = document.getElementById(templateId);

        if (!template) {
            keluargaSection.classList.add("hidden");
            return;
        }

        keluargaSection.innerHTML = template.innerHTML;
        isiNilaiLama(keluargaSection);
        keluargaSection.classList.remove("hidden");
    }

    function terapkanDomisili() {
        if (!domisili || !alamatDomisiliContainer || !alamatDomisili) {
            return;
        }

        if (domisili.value === "lain") {
            alamatDomisiliContainer.classList.remove("hidden");
            return;
        }

        alamatDomisiliContainer.classList.add("hidden");
        alamatDomisili.value = "";
    }

    if (jenisKredit) {
        jenisKredit.addEventListener("change", terapkanJenisKredit);
    }

    if (statusNikah) {
        statusNikah.addEventListener("change", renderKeluarga);
    }

    if (domisili) {
        domisili.addEventListener("change", terapkanDomisili);
    }

    function isVisible(field) {
        if (field.disabled) {
            return false;
        }

        if (field.closest(".hidden") || field.closest(".pk-jebakan")) {
            return false;
        }

        const style = window.getComputedStyle(field);

        return style.display !== "none" && style.visibility !== "hidden";
    }

    function getFieldLabel(field) {
        const group = field.closest(".form-group");

        if (group) {
            const label = group.querySelector("label");

            if (label) {
                return label.textContent.replace(/\s+/g, " ").trim();
            }
        }

        if (field.type === "checkbox") {
            return "Persetujuan";
        }

        return "Kolom ini";
    }

    function validateFile(file) {
        const allowedExtensions = ["pdf", "jpg", "jpeg", "png"];
        const maxSize = 5 * 1024 * 1024;
        const extension = file.name.split(".").pop().toLowerCase();

        if (!allowedExtensions.includes(extension)) {
            return "Format file harus PDF, JPG, JPEG, atau PNG.";
        }

        if (file.size > maxSize) {
            return "Ukuran file maksimal 5 MB.";
        }

        return null;
    }

    function validateEmail(email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    }

    function validateForm() {
        const fields = form.querySelectorAll("input, select, textarea");

        for (const field of fields) {
            if (!isVisible(field)) {
                continue;
            }

            if (field.type === "checkbox") {
                if (!field.checked) {
                    showPopup("Silakan centang persetujuan terlebih dahulu.");
                    field.focus();
                    return false;
                }

                continue;
            }

            if (field.type === "file") {
                if (field.files.length === 0) {
                    showPopup(getFieldLabel(field) + " wajib diupload.");
                    field.focus();
                    return false;
                }

                const fileError = validateFile(field.files[0]);

                if (fileError) {
                    showPopup(getFieldLabel(field) + ": " + fileError);
                    field.focus();
                    return false;
                }

                continue;
            }

            if (field.tagName === "SELECT") {
                if (field.selectedIndex === 0 || field.value.trim() === "") {
                    showPopup(getFieldLabel(field) + " belum dipilih.");
                    field.focus();
                    return false;
                }

                continue;
            }

            if (field.value.trim() === "") {
                showPopup(getFieldLabel(field) + " belum diisi.");
                field.focus();
                return false;
            }

            if (field.type === "email" && !validateEmail(field.value.trim())) {
                showPopup("Format email tidak valid.");
                field.focus();
                return false;
            }
        }

        return true;
    }

    form.addEventListener("submit", function (event) {
        event.preventDefault();

        if (!validateForm()) {
            return;
        }

        // Lewati handler ini agar formulir benar-benar dikirim ke server
        HTMLFormElement.prototype.submit.call(form);
    });

    // Saat halaman baru dibuka atau dipulihkan dari riwayat, kosongkan pilihan
    // bersyarat (perilaku yang sama dengan formulir native). Kalau halaman ini
    // hasil validasi gagal, nilai yang sudah diisi justru dipulihkan.
    function siapkanFormulir() {
        const segarkan = form.dataset.segarkan === "1";

        if (segarkan) {
            if (jenisKredit) {
                jenisKredit.value = "";
            }

            if (statusNikah) {
                statusNikah.value = "";
            }

            if (domisili) {
                domisili.value = "";
            }
        }

        terapkanJenisKredit();
        renderKeluarga();
        terapkanDomisili();
        formatNominal();
    }

    siapkanFormulir();

    // Nilai lama hanya dipakai sekali saat halaman dimuat
    nilaiKeluarga = {};

    window.addEventListener("pageshow", function (event) {
        if (event.persisted) {
            siapkanFormulir();
        }
    });
});
