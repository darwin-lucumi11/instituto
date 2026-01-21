console.log("app.js cargado correctamente");

document.addEventListener("DOMContentLoaded", () => {
  const $ = s => document.querySelector(s);
  const $$ = s => Array.from(document.querySelectorAll(s));

  /* =============================
        NAVEGACIÓN ENTRE SECCIONES
  ============================== */
  const links = $$("[data-route]");
  const sections = $$("main section");

  function showSection(id) {
    sections.forEach(sec => sec.hidden = sec.id !== id);
    window.scrollTo(0, 0);
  }

  links.forEach(link => {
    link.addEventListener("click", e => {
      e.preventDefault();
      const id = link.getAttribute("href").replace("#", "");
      showSection(id);
      history.pushState(null, "", `#${id}`);
    });
  });

  const start = location.hash ? location.hash.replace("#", "") : "dashboard";
  showSection(start);


  /* =============================
        CARGAR ASIGNATURAS
  ============================== */
  function loadAsignaturas() {
    fetch("cargar_asignaturas.php")
      .then(r => r.json())
      .then(data => {
        $$("select[name='id_asignatura'], select[name='asignatura']").forEach(sel => {
          sel.innerHTML = `<option value="">-- Seleccione --</option>`;
          data.forEach(a => {
            const o = document.createElement("option");
            o.value = a.id_asignatura;
            o.textContent = a.nombre;
            sel.appendChild(o);
          });
        });
      });
  }
  loadAsignaturas();


  /* =============================
        CARGAR ESTUDIANTES (SELECT)
  ============================== */
  function loadStudentsSelect() {
    fetch("listar_estudiantes.php")
      .then(r => r.json())
      .then(data => {
        const selects = $$("select[name='estudiante'], select[name='id_estudiante']");

        selects.forEach(sel => {
          sel.innerHTML = `<option value="">-- Seleccione --</option>`;
          data.forEach(e => {
            const opt = document.createElement("option");
            opt.value = e.id_estudiante;
            opt.textContent = `${e.documento} - ${e.nombres} ${e.apellidos}`;
            sel.appendChild(opt);
          });
        });
      });
  }
  loadStudentsSelect();


  /* =============================
        LISTAR ESTUDIANTES (TABLA)
  ============================== */
  function loadStudents() {
    const tbody = $("#studentTable");
    if (!tbody) return;

    fetch("listar_estudiantes.php")
      .then(r => r.json())
      .then(data => {
        tbody.innerHTML = "";

        if (!data.length) {
          tbody.innerHTML = `<tr><td colspan="5">No hay estudiantes registrados.</td></tr>`;
          $("#kpiStudents").textContent = 0;
          return;
        }

        data.forEach(s => {
          const tr = document.createElement("tr");
          tr.innerHTML = `
            <td>${s.documento}</td>
            <td>${s.nombres} ${s.apellidos}</td>
            <td>${s.telefono}</td>
            <td>${s.asignatura || ""}</td>
            <td><button class="btn danger" onclick="deleteStudent(${s.id_estudiante})">
             🗑️
            </button></td>`;
          tbody.appendChild(tr);
        });

        $("#kpiStudents").textContent = data.length;
      });
  }
  loadStudents();
 window.deleteStudent = function(id) {
  if (!confirm("¿Seguro que deseas eliminar este estudiante?")) return;

  fetch("eliminar_estudiante.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded"
    },
    body: `id_estudiante=${id}`
  })
    .then(r => r.json())
    .then(resp => {
      if (!resp.ok) {
        alert(resp.msg || "Error al eliminar");
        return;
      }

      alert("🗑️ Estudiante eliminado correctamente");
      loadStudents();
      loadStudentsSelect();
    })
    .catch(err => {
      console.error(err);
      alert("Error de conexión");
    });
};




  /* =============================
        FORMULARIO DE ESTUDIANTES
  ============================== */
  const studentForm = $("#studentForm");

  if (studentForm) {
    studentForm.addEventListener("submit", e => {
      e.preventDefault();
      const fd = new FormData(studentForm);

      fetch("insertar_estudiantes.php", {
        method: "POST",
        body: fd
      })
        .then(r => r.json())
        .then(resp => {
          const toast = $("#toast");
          if (toast) {
            toast.textContent = resp.msg;
            toast.classList.add("show");
            setTimeout(() => toast.classList.remove("show"), 3000);
          } else alert(resp.msg);

          if (resp.ok) {
            studentForm.reset();
            $("#studentModal")?.close();
            loadStudents();
            loadStudentsSelect();
          }
        });
    });
  }


  /* =============================
        MODAL DE ESTUDIANTES
  ============================== */
  const modal = $("#studentModal");
  $("#newStudentBtn")?.addEventListener("click", () => modal.showModal());
  $("#closeModal")?.addEventListener("click", () => modal.close());
  modal?.addEventListener("click", e => { if (e.target === modal) modal.close(); });


  /* =============================
        FORMULARIO DE PAGOS
  ============================== */
  const pagoForm = $("#pagoForm");

  if (pagoForm) {
    pagoForm.addEventListener("submit", e => {
      e.preventDefault();

      const datos = new FormData(pagoForm);

      fetch("insertar_pago.php", {
        method: "POST",
        body: datos
      })
        .then(r => r.json())
        .then(d => {
          alert(d.status === "success" ? "Pago registrado correctamente" : "Error: " + d.message);

          if (d.status === "success") {
            pagoForm.reset();
            cargarPagos();
          }
        });
    });
  }


  /* =============================
        LISTAR HISTORIAL DE PAGOS
  ============================== */
  async function cargarPagos() {
    const res = await fetch("listar_pagos.php");
    const pagos = await res.json();

    const tbody = $("#paymentTable");
    tbody.innerHTML = "";

    pagos.forEach(p => {
      const tr = document.createElement("tr");
      tr.innerHTML = `
        <td>${p.nombres} ${p.apellidos}</td>
        <td>${p.asignatura}</td>
        <td>$${Number(p.valor).toLocaleString()}</td>
        <td>${p.fecha}</td>
        <td>${p.estado}</td>`;
        
      tbody.appendChild(tr);
    });
  }

  cargarPagos();
  const selectEstado = document.querySelector('select[name="estado"]');

if (selectEstado) {
  function actualizarColorEstado() {
    selectEstado.classList.remove("estado-pagado", "estado-pendiente");

    if (selectEstado.value === "pagado") {
      selectEstado.classList.add("estado-pagado");
    } 
    else if (selectEstado.value === "pendiente") {
      selectEstado.classList.add("estado-pendiente");
    }
  }

  // Cambiar al cargar la página
  actualizarColorEstado();

  // Cambiar cuando modifiquen el select
  selectEstado.addEventListener("change", actualizarColorEstado);
}



  /* =============================
        MENÚ LATERAL
  ============================== */
  const openMenuBtn = $("#openMenu");
  const sidebar = $(".sidebar");
  const backdrop = $("#backdrop");

  openMenuBtn?.addEventListener("click", () => {
    const opened = sidebar.hasAttribute("open");
    sidebar.toggleAttribute("open", !opened);
    backdrop.toggleAttribute("show", !opened);
  });

  backdrop?.addEventListener("click", () => {
    sidebar.removeAttribute("open");
    backdrop.removeAttribute("show");
  });


  /* =============================
        LOGOUT
  ============================== */
  $("#logoutBtn")?.addEventListener("click", () => {
    if (confirm("¿Deseas cerrar sesión?"))
      window.location.href = "logout.php";
  });


  /* =============================
        EXPORTAR CSV
  ============================== */
  $("#exportCSV")?.addEventListener("click", () => {
    window.location.href = "exportar_estudiantes_csv.php";
  });
});


/* =============================
      ANIMACIÓN DE TARJETAS
============================= */
document.querySelectorAll(".card").forEach(card => {
  card.addEventListener("mousemove", e => {
    const r = card.getBoundingClientRect();
    card.style.setProperty("--mx", ((e.clientX - r.left) / r.width) * 100 + "%");
    card.style.setProperty("--my", ((e.clientY - r.top) / r.height) * 100 + "%");
  });
});


/* =============================
      BUSCADOR GLOBAL
============================= */
const globalSearch = document.getElementById("globalSearch");

if (globalSearch) {
  globalSearch.addEventListener("input", () => {
    const q = globalSearch.value.toLowerCase().trim();
    const section = document.querySelector("main section:not([hidden])");
    if (!section) return;

    const elements = section.querySelectorAll(`
      p, span, td, th, li,
      h1, h2, h3, h4,
      label, .card, .pill
    `);

    elements.forEach(el => {
      const t = el.textContent.toLowerCase();
      el.style.display = q === "" || t.includes(q) ? "" : "none";
    });
  });
}
// ==============================
// VALIDAR USUARIO EN TIEMPO REAL
// ==============================
const inputUsuario = document.getElementById("usuario");
const estadoUsuario = document.getElementById("estadoUsuario");

if (inputUsuario && estadoUsuario) {
  inputUsuario.addEventListener("input", async () => {
    const usuario = inputUsuario.value.trim();

    if (usuario.length < 3) {
      estadoUsuario.textContent = "";
      estadoUsuario.className = "";
      return;
    }

    try {
      const res = await fetch("verificar_usuario.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded"
        },
        body: "usuario=" + encodeURIComponent(usuario)
      });

      const data = await res.json();

      if (data.existe) {
        estadoUsuario.textContent = "❌ Usuario no disponible";
        estadoUsuario.style.color = "red";
      } else {
        estadoUsuario.textContent = "✔ Usuario disponible";
        estadoUsuario.style.color = "green";
      }

    } catch (error) {
      console.error(error);
      estadoUsuario.textContent = "";
    }
  });
}


// ==============================
// REGISTRAR USUARIO
// ==============================
const registroForm = document.getElementById("registroForm");

if (registroForm) {
  registroForm.addEventListener("submit", async (e) => {
    e.preventDefault();

    const formData = new FormData(registroForm);

    try {
      const res = await fetch("registrar_usuario.php", {
        method: "POST",
        body: formData
      });

      const data = await res.json();

      if (data.success) {
        alert(data.message || "Usuario registrado correctamente");
        registroForm.reset();
        if (estadoUsuario) estadoUsuario.textContent = "";
      } else {
        alert(data.message || "Error al registrar usuario");
      }

    } catch (error) {
      console.error(error);
      alert("Error del servidor");
    }
  });
}
// ==============================
//     REGISTRAR SOLICITUD
// ==============================
const requestForm = document.getElementById("requestForm");

if (requestForm) {
    requestForm.addEventListener("submit", async (e) => {
        e.preventDefault();

        const fd = new FormData(requestForm);

        try {
            const res = await fetch("insertar_solicitud.php", {
                method: "POST",
                body: fd
            });

            const data = await res.json();

            alert(data.message || "Solicitud procesada");

            if (data.success) {
                requestForm.reset();
                cargarSolicitudes();
            }

        } catch (error) {
            console.error(error);
            alert("Error de servidor");
        }
    });
}



// ==============================
//     LISTAR SOLICITUDES
// ==============================
async function cargarSolicitudes() {
    const cont = document.getElementById("reqList");
    if (!cont) return;

    const res = await fetch("listar_solicitudes.php");
    const data = await res.json();

    cont.innerHTML = "";

    if (!data.length) {
        cont.innerHTML = "<p>No hay solicitudes registradas.</p>";
        return;
    }

    data.forEach(s => {
        const div = document.createElement("div");
        div.classList.add("card", "shadow");

        div.innerHTML = `
            <p><b>ID:</b> ${s.id}</p>
            <p><b>Documento:</b> ${s.documento}</p>
            <p><b>Tipo:</b> ${s.tipo}</p>
            <p><b>Detalle:</b> ${s.detalle}</p>
            <p><b>Fecha:</b> ${s.fecha}</p>
        `;

        cont.appendChild(div);
    });
}

// Ejecutar al cargar
cargarSolicitudes();

document.addEventListener('DOMContentLoaded', () => {

  const form = document.getElementById('notifyForm');
  const radios = document.querySelectorAll('input[name="to"]');
  const rowStudent = document.getElementById('row-student');
  const rowCourse = document.getElementById('row-course');
  const courseSelect = document.getElementById('courseSelect');

  /* === MOSTRAR / OCULTAR CAMPOS === */
  function updateDestino() {
  const selected = document.querySelector('input[name="to"]:checked').value;

  rowStudent.hidden = true;
  rowCourse.hidden = true;

  if (selected === 'one') rowStudent.hidden = false;
  if (selected === 'course') rowCourse.hidden = false;
}

// escuchar cambios
radios.forEach(radio => {
  radio.addEventListener('change', updateDestino);
});

// ejecutar al cargar la página
updateDestino();


  /* === CARGAR CURSOS DESDE BD === */
  fetch('get_asignaturas.php')
    .then(res => res.json())
    .then(data => {
      data.forEach(curso => {
        const opt = document.createElement('option');
        opt.value = curso.id_asignatura;
        opt.textContent = curso.nombre;
        courseSelect.appendChild(opt);
      });
    })
    .catch(err => console.error('Error cargando cursos', err));

  /* === ENVÍO === */
  form.addEventListener('submit', async (e) => {
    e.preventDefault();

    const data = new FormData(form);
    
    // 🔑 AJUSTE CLAVE (AQUÍ)
    if (data.get('to') === 'one' && selectedStudentId) {
     data.set('student', selectedStudentId);
    
    }
    

    // 🔑 CLAVE: cuando es TODOS, no enviar student
    if (data.get('to') === 'all') {
      data.delete('student');
      data.delete('course');
    }


    // Normalizar curso
    if (data.get('to') === 'course') {
      data.set('student', data.get('course'));
    }
    

    const res = await fetch('notificaciones_enviar.php', {
      method: 'POST',
      body: data
    });

    const json = await res.json();

    if (!json.ok) {
      alert(json.error || 'Error al enviar notificación');
      return;
    }

    alert(`✅ Notificación enviada a ${json.phones.length} estudiantes`);

    // Abrir WhatsApp Web (primer número)
    const phone = json.phones[0];
    const text = encodeURIComponent(json.body);
    window.open(`https://wa.me/${phone}?text=${text}`, '_blank');
  });

});
document.addEventListener('DOMContentLoaded', () => {

  const studentInput = document.getElementById('studentInput');
  const studentResults = document.getElementById('studentResults');

  if (studentInput && studentResults) {
    let selectedStudentId = null;

    studentInput.addEventListener('input', () => {
      const q = studentInput.value.trim();
      selectedStudentId = null;

      if (q.length < 2) {
        studentResults.style.display = 'none';
        return;
      }

      fetch(`/instituto/buscar_estudiantes.php?q=${encodeURIComponent(q)}`)
        .then(res => res.json())
        .then(data => {
          studentResults.innerHTML = '';
          if (data.length === 0) {
            studentResults.style.display = 'none';
            return;
          }

          data.forEach(est => {
            const div = document.createElement('div');
            div.textContent = `${est.documento} - ${est.nombres} ${est.apellidos}`;
            div.onclick = () => {
              studentInput.value = `${est.nombres} ${est.apellidos}`;
              selectedStudentId = est.id_estudiante;
              studentResults.style.display = 'none';
            };
            studentResults.appendChild(div);
          });

          studentResults.style.display = 'block';
        });
    });
  }

  /* ===== CURSOS ===== */

  const courseForm = document.getElementById('courseForm');
  const tbody = document.querySelector('#tablaCursos tbody');

  if (courseForm && tbody) {

    function cargarCursos() {
      fetch('cursos_listar.php')
        .then(r => r.json())
        .then(data => {
          tbody.innerHTML = '';
          data.forEach(c => {
            tbody.innerHTML += `
              <tr>
                <td>#${c.id_asignatura}</td>
                <td>${c.nombre}</td>
                <td>${c.intensidad_horaria}</td>
                <td>
                  <button onclick="eliminarCurso(${c.id_asignatura})">🗑</button>
                </td>
              </tr>`;
          });
        });
    }

    cargarCursos();

    courseForm.addEventListener('submit', e => {
      e.preventDefault();
      const data = new FormData(courseForm);

      fetch('cursos_guardar.php', {
        method: 'POST',
        body: data
      })
      .then(r => r.json())
      .then(res => {
        if (res.ok) {
          courseForm.reset();
          cargarCursos();
          alert('✅ Curso guardado');
        }
      });
    });

    window.eliminarCurso = function(id) {
      if (!confirm('¿Eliminar curso?')) return;

      fetch('cursos_eliminar.php', {
        method: 'POST',
        body: new URLSearchParams({ id })
      })
      .then(r => r.json())
      .then(() => cargarCursos());
    }
  }

});
// =====kpi_cursos.php======//
fetch("kpi_cursos.php")
  .then(r => r.json())
  .then(d => {
    const el = document.getElementById("kpiCourses");
    if (el) el.textContent = d.total;
  });

/* =============================
   KPI SOLICITUDES
============================= */
function loadRequestsKPI() {
  fetch("kpi_solicitudes.php")
    .then(r => r.json())
    .then(d => {
      const el = document.getElementById("kpiReqs");
      if (el) el.textContent = d.total;
    })
    .catch(() => {
      const el = document.getElementById("kpiReqs");
      if (el) el.textContent = 0;
    });
}

loadRequestsKPI();




