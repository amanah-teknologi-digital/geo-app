document.addEventListener("DOMContentLoaded", function() {
    {
        var w = document.getElementById("calendar");
        let t = document.querySelector(".app-calendar-sidebar");
        var x = document.getElementById("addEventSidebar");
        let n = document.querySelector(".app-overlay")
            , a = document.querySelector(".offcanvas-title");
        var T = document.querySelector(".btn-toggle-sidebar");
        let l = document.getElementById("addEventBtn")
            , i = document.querySelector(".btn-delete-event")
            , r = document.querySelector(".btn-cancel")
            , d = document.getElementById("eventTitle")
            , o = document.getElementById("eventStartDate")
            , s = document.getElementById("eventEndDate")
            , c = document.getElementById("eventURL")
            , u = document.getElementById("eventLocation")
            , v = document.getElementById("eventDescription")
            , m = document.querySelector(".allDay-switch")
            , p = document.querySelector(".select-all");
        var D, P, M = Array.from(document.querySelectorAll(".input-filter")), A = document.querySelector(".inline-calendar");
        let g = {
            Business: "primary",
            Holiday: "success",
            Personal: "danger",
            Family: "warning",
            ETC: "info"
        }
            , f = $("#eventLabel")
            , h = $("#eventGuests")
            , y = !1
            , E = null
            , e = null
            , L = new bootstrap.Offcanvas(x);
        function q(e) {
            return e.id ? "<span class='badge badge-dot bg-" + $(e.element).data("label") + " me-2'> </span>" + e.text : e.text
        }
        function B(e) {
            return e.id ? `
    <div class='d-flex flex-wrap align-items-center'>
      <div class='avatar avatar-xs me-2'>
        <img src='${assetsPath}img/avatars/${$(e.element).data("avatar")}'
          alt='avatar' class='rounded-circle' />
      </div>
      ${e.text}
    </div>` : e.text
        }
        function I() {
            var e = document.querySelector(".fc-sidebarToggle-button");
            for (e.classList.remove("fc-button-primary"),
                     e.classList.add("d-lg-none", "d-inline-block", "ps-0"); e.firstChild; )
                e.firstChild.remove();
            e.setAttribute("data-bs-toggle", "sidebar"),
                e.setAttribute("data-overlay", ""),
                e.setAttribute("data-target", "#app-calendar-sidebar"),
                e.insertAdjacentHTML("beforeend", '<i class="icon-base bx bx-menu icon-lg text-heading"></i>')
        }
        f.length && f.wrap('<div class="position-relative"></div>').select2({
            placeholder: "Select value",
            dropdownParent: f.parent(),
            templateResult: q,
            templateSelection: q,
            minimumResultsForSearch: -1,
            escapeMarkup: function(e) {
                return e
            }
        }),
        h.length && h.wrap('<div class="position-relative"></div>').select2({
            placeholder: "Select value",
            dropdownParent: h.parent(),
            closeOnSelect: !1,
            templateResult: B,
            templateSelection: B,
            escapeMarkup: function(e) {
                return e
            }
        }),
        o && (D = o.flatpickr({
            monthSelectorType: "static",
            static: !0,
            enableTime: !0,
            altFormat: "Y-m-dTH:i:S",
            onReady: function(e, t, n) {
                n.isMobile && n.mobileInput.setAttribute("step", null)
            }
        })),
        s && (P = s.flatpickr({
            monthSelectorType: "static",
            static: !0,
            enableTime: !0,
            altFormat: "Y-m-dTH:i:S",
            onReady: function(e, t, n) {
                n.isMobile && n.mobileInput.setAttribute("step", null)
            }
        })),
        A && (e = A.flatpickr({
            monthSelectorType: "static",
            static: !0,
            inline: !0
        }));
        function F() {
            s.value = "",
                c.value = "",
                o.value = "",
                d.value = "",
                u.value = "",
                m.checked = !1,
                h.val("").trigger("change"),
                v.value = ""
        }
            I(),
        T && T.addEventListener("click", e => {
                r.classList.remove("d-none")
            }
        ),
            l.addEventListener("click", e => {
                    var t, n;
                    l.classList.contains("btn-add-event") ? y && (n = {
                        id: S.getEvents().length + 1,
                        title: d.value,
                        start: o.value,
                        end: s.value,
                        startStr: o.value,
                        endStr: s.value,
                        display: "block",
                        extendedProps: {
                            location: u.value,
                            guests: h.val(),
                            calendar: f.val(),
                            description: v.value
                        }
                    },
                    c.value && (n.url = c.value),
                    m.checked && (n.allDay = !0),
                        n = n,
                        b.push(n),
                        S.refetchEvents(),
                        L.hide()) : y && (n = {
                        id: E.id,
                        title: d.value,
                        start: o.value,
                        end: s.value,
                        url: c.value,
                        extendedProps: {
                            location: u.value,
                            guests: h.val(),
                            calendar: f.val(),
                            description: v.value
                        },
                        display: "block",
                        allDay: !!m.checked
                    },
                        (t = n).id = parseInt(t.id),
                        b[b.findIndex(e => e.id === t.id)] = t,
                        S.refetchEvents(),
                        L.hide())
                }
            ),
            i.addEventListener("click", e => {
                    var t;
                    t = parseInt(E.id),
                        b = b.filter(function(e) {
                            return e.id != t
                        }),
                        S.refetchEvents(),
                        L.hide()
                }
            ),
            x.addEventListener("hidden.bs.offcanvas", function() {
                F()
            }),
            T.addEventListener("click", e => {
                    a && (a.innerHTML = "Add Event"),
                        l.innerHTML = "Add",
                        l.classList.remove("btn-update-event"),
                        l.classList.add("btn-add-event"),
                        i.classList.add("d-none"),
                        t.classList.remove("show"),
                        n.classList.remove("show")
                }
            ),
        p && p.addEventListener("click", e => {
                e.currentTarget.checked ? document.querySelectorAll(".input-filter").forEach(e => e.checked = 1) : document.querySelectorAll(".input-filter").forEach(e => e.checked = 0),
                    S.refetchEvents()
            }
        ),
        M && M.forEach(e => {
                e.addEventListener("click", () => {
                        document.querySelectorAll(".input-filter:checked").length < document.querySelectorAll(".input-filter").length ? p.checked = !1 : p.checked = !0,
                            S.refetchEvents()
                    }
                )
            }
        ),
            e.config.onChange.push(function(e) {
                S.changeView(S.view.type, moment(e[0]).format("YYYY-MM-DD")),
                    I(),
                    t.classList.remove("show"),
                    n.classList.remove("show")
            })
    }
});

