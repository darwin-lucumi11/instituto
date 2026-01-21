<?php
session_start();
if(!isset($_SESSION['usuario'])){ header('Location: login.php'); exit; }
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Instituto San Felipe</title>
  <link rel="stylesheet" href="style.css"/>
</head>
<body>
  
  <div class="app">
    <!-- SIDEBAR -->
    <aside class="sidebar">
      <div class="logo">
        <div class="logo-badge">ISF</div>
        <div>
          <span class="muted">Instituto San Felipe</span>
          <button class="btn ok" id="logoutBtn">🚪 Cerrar sesión</button>
        </div>
      </div>
      

      <nav>
        <a class="nav-item" href="#dashboard" data-route>🏠 Panel</a>
        <a class="nav-item" href="#estudiantes" data-route>👩‍🎓 Estudiantes</a>
        <a class="nav-item" href="#cursos" data-route>📚 Cursos</a>
        <a class="nav-item" href="#solicitudes" data-route>📝 Solicitudes</a>
        <a class="nav-item" href="#citas" data-route>📅 Citas</a>
        <a class="nav-item" href="#reportes" data-route>📊 Reportes</a>
        <a class="nav-item" href="#notificaciones" data-route>🔔 Notificaciones</a>
        <a class="nav-item" href="#pagos" data-route>💰 Pagos</a>
        <a class="nav-item" href="#seguridad" data-route>🔐 Seguridad</a>
      </nav>

      <div class="kpi">
        <div><strong>Estado:</strong> Operativo</div>
        <div class="muted">Último despliegue: hoy</div>
      </div>
    </aside>
    
    <div class="backdrop" id="backdrop" aria-hidden="true"></div>
    <!-- MAIN -->
    <main>
      <header>
        <button class="btn menu-btn" id="openMenu">☰</button>
        <div class="search">
          🔎 <input id="globalSearch" type="search" placeholder="Buscar..." />
        </div>
        <div class="userbar">
          <button class="btn primary" id="newStudentBtn">➕ Estudiante</button>
        </div>
      </header>

      <div class="content">
        <!-- PANEL -->
        <section id="dashboard">
          <div class="grid cols-3">
            <div class="card">
              <h2>Panel</h2>
              <p class="muted">Resumen rápido del instituto.</p>
              <div class="kpi-row">
                <div class="pill">👩‍🎓 Estudiantes: <b id="kpiStudents">0</b></div>
                <div class="pill">📚 Cursos: <b id="kpiCourses">0</b></div>
                <div class="pill">📝 Solicitudes: <b id="kpiReqs">0</b></div>
                <div class="pill">📅 Citas: <b id="kpiAppts">0</b></div>
              </div>
            </div>
            <div class="card">
              <h2>Actividad reciente</h2>
              <ul class="muted">
                <li>Se creó el prototipo SIGI.</li>
              </ul>
            </div>
            <div class="card">
              <h2>Atajos</h2>
              <div style="display:flex; gap:10px; flex-wrap:wrap">
                <a class="btn ok" href="#estudiantes" data-route>Gestionar estudiantes</a>
                <a class="btn warn" href="#cursos" data-route>Registrar curso</a>
                <a class="btn" href="#solicitudes" data-route>Ver solicitudes</a>
              </div>
            </div>
          </div>
        </section>

        <!-- ESTUDIANTES -->
        <section id="estudiantes" hidden>
          <div class="card">
            <h2>Estudiantes</h2>
            <div style="display:flex; gap:10px; align-items:center; margin-bottom:12px">
              <input class="input" id="studentFilter" placeholder="Filtrar..." />
              <button class="btn" id="exportCSV">⬇️ Exportar CSV</button>
            </div>
            <div class="card" style="padding:0">
              <table class="table">
                <thead>
                  <tr>
                    <th>Documento</th>
                    <th>Nombre</th>
                    <th>Teléfono</th>
                    <th>Asignatura</th>
                    <th>Acciones</th> 
                  </tr>
                </thead>
                <tbody id="studentTable"></tbody>
              </table>
            </div>
          </div>
        </section>

        <!-- CURSOS -->
        <section id="cursos" hidden>
  <div class="card">
    <h2>Cursos</h2>

    <form  class="form two" id="courseForm" novalidate>
      <div class="row">
        <label>Código (automático)
          <input class="input" disabled placeholder="Se genera automáticamente">
        </label>
      </div>

      <div class="row">
        <label>Nombre
          <input class="input" name="nombre" required minlength="3">
        </label>
      </div>

       <div class="row" style="grid-column:1 / -1">
         <label>Descripción
           <input class="input" name="descripcion">
         </label>
       </div>

      <div class="row">
       <label>Intensidad horaria
         <input class="input" type="number" name="intensidad_horaria" required>
       </label>
     </div>

      <div class="row" style="grid-column:1 / -1">
        <button class="btn primary" type="submit">Guardar</button>
      </div>
    </form>

    <hr>

    <table class="table" id="tablaCursos">
      <thead>
        <tr>
          <th>ID</th>
          <th>Nombre</th>
          <th>Horas</th>
          <th>Acción</th>
        </tr>
      </thead>
      <tbody></tbody>
    </table>
  </div>
</section>

        <!-- SOLICITUDES -->
        <section id="solicitudes" hidden>
          <div class="card">
            <h2>Solicitudes estudiantiles</h2>
            <form class="form two" id="requestForm" novalidate>
              <div class="row">
                <label>Documento estudiante
                  <select class="input" name="id_estudiante" required>
                   <option value="">Seleccione…</option>
                  </select>
                  
                </label>
              </div>
              <div class="row">
                <label>Tipo
                  <select name="type">
                    <option>Certificado</option>
                    <option>Constancia</option>
                    <option>Cambio de grupo</option>
                  </select>
                </label>
              </div>
              <div class="row" style="grid-column:1 / -1">
                <label>Detalle
                  <textarea class="input" name="detail" rows="3" placeholder="Describe tu solicitud…"></textarea>
                </label>
              </div>
              <div class="row" style="grid-column:1 / -1; display:flex; gap:10px; justify-content:flex-end">
                <button class="btn" type="reset">Limpiar</button>
                <button class="btn primary" type="submit">Registrar</button>
              </div>
            </form>
          </div>
           <div class="card">
            <h2>Bandeja</h2>
            <div id="reqList" class="grid"></div>
          </div>
        </section>

        <!-- CITAS -->
        <section id="citas" hidden>
          <div class="card">
            <h2>Citas administrativas</h2>
            <form class="form two" id="apptForm" novalidate>
              <div class="row">
                <label>Documento estudiante
                  <input class="input" name="doc" required />
                </label>
              </div>
              <div class="row">
                <label>Fecha y hora
                  <input class="input" type="datetime-local" name="when" required />
                </label>
              </div>
              <div class="row" style="grid-column:1 / -1; display:flex; gap:10px; justify-content:flex-end">
                <button class="btn" type="reset">Limpiar</button>
                <button class="btn primary" type="submit">Agendar</button>
              </div>
            </form>
          </div>
        </section>

        <!-- REPORTES -->
        <section id="reportes" hidden aria-labelledby="rep-title">
          <div class="card">
            <h2 id="rep-title">Reportes y estadísticas</h2>
            <p class="muted">Demostración simple (sin librerías externas):</p>
            <canvas id="miniChart" width="640" height="220" aria-label="Gráfico de barras de ejemplo" role="img"></canvas>
          </div>
        </section>

        <!-- NOTIFICACIONES -->
        <section id="notificaciones" hidden>
          <div class="card">
            <h2>Notificaciones</h2>
            <form class="form two" id="notifyForm" method="post"novalidate>
              <!-- DESTINO -->
             <div class="row" style="grid-column:1 / -1">
               <label>Destino</label>
               <label><input type="radio" name="to" value="all" checked> Todos los estudiantes</label>
               <label><input type="radio" name="to" value="course"> Un curso</label>
               <label><input type="radio" name="to" value="one"> Un estudiante</label>
             </div>
              <!-- CURSO -->
             <div class="row" id="row-course" hidden>
               <label>Curso
                 <select class="input" name="course" id="courseSelect">
                   <option value="">Seleccione un curso</option>
                 </select>
               </label>
             </div>

              <!-- ESTUDIANTE -->
              <div class="row" id="row-student" hidden>
                <label>Estudiante
                  <input class="input" name="student" placeholder="ID, documento o nombre" />
               </label>
                <div id="studentResults" style="border:1px solid #ccc; background:#fff; position:relative; z-index:10; display:none"></div>
              </div>
              <!-- MENSAJE -->
              <div class="row" style="grid-column:1 / -1">
                <label>Mensaje
                 <textarea class="input" name="body" rows="4" required placeholder="Escribe el mensaje…"></textarea>
                </label>
             </div>
              <div class="row" style="grid-column:1 / -1; display:flex; gap:10px; justify-content:flex-end">
                <button class="btn" type="reset">Limpiar</button>
                <button class="btn primary" type="submit">Enviar</button>
              </div>
              <!-- INFO -->
               <div class="row" style="grid-column:1 / -1">
                <small style="opacity:.7">📲 El mensaje se enviará por WhatsApp Web</small>
             </div>

            </form>
          </div>
        </section>

        <!-- PAGOS -->
<section id="pagos" hidden>
  <div class="card">
    <h2>Registrar Pago de Mensualidad</h2>

    <form class="form two" id="pagoForm" novalidate>
      
      <div class="row">
        <label>Estudiante
          <select name="estudiante" required></select>
        </label>
      </div>

      <div class="row">
        <label>Asignatura
          <select name="asignatura" required></select>
        </label>
      </div>

      <div class="row">
        <label>Valor Mensual
          <input class="input" type="number" name="valor" required placeholder="Ej: 150000">
        </label>
      </div>

      <div class="row">
        <label>Fecha de Pago
          <input class="input" type="date" name="fecha" required>
        </label>
      </div>

      <div class="row">
        <label>Estado
          <select name="estado" required>
            <option value="pendiente">Pendiente</option>
            <option value="pagado">Pagado</option>
          </select>
        </label>
      </div>

      <div class="row" style="grid-column:1 / -1; display:flex; gap:10px; justify-content:flex-end">
        <button class="btn" type="reset">Limpiar</button>
        <button class="btn primary" type="submit">Registrar Pago</button>
      </div>

    </form>
  </div>

   <div class="card">
    <h3>Historial de Pagos</h3>
    <table class="table" id="tablaPagos">
      <thead>
        <tr>
          <th>Estudiante</th>
          <th>Asignatura</th>
          <th>Valor</th>
          <th>Fecha</th>
          <th>Estado</th>
        </tr>
      </thead>
      <tbody id="paymentTable"></tbody>
    </table>
  </div>
</section>


        <!-- SEGURIDAD -->
        <section id="seguridad" hidden>
          <div class="card">
            <h2>Seguridad y accesos</h2>
            <ul class="muted">
              <li>Perfiles: Administrador, Docente, Estudiante</li>
              <li>Buenas prácticas: contraseñas cifradas, control de sesiones, mínimo privilegio.</li>
              <li>Auditoría: trazabilidad de acciones y cambios.</li>
            </ul>
          </div>
        </section>
      </div>
    </main>
  </div>

  <!-- MODAL NUEVO ESTUDIANTE -->
  <dialog class="modal" id="studentModal">
    <div class="panel">
      <header><h3>Nuevo estudiante</h3></header>
      <div class="body">
        <form class="form two" id="studentForm" action="insertar_estudiantes.php" method="POST">
          <div class="row"><label>Documento<input class="input" name="documento" required /></label></div>
          <div class="row"><label>Nombres<input class="input" name="nombres" required /></label></div>
          <div class="row"><label>Apellidos<input class="input" name="apellidos" required /></label></div>
          <div class="row"><label>Teléfono<input class="input" name="telefono" required /></label></div>
          <div class="row"><label>Asignatura<select name="id_asignatura" required><option value="">-- Seleccione --</option></select></label></div>
          <div class="row" style="grid-column:1/-1;display:flex;gap:10px;justify-content:flex-end">
            <button class="btn" type="button" id="closeModal">Cancelar</button>
            <button class="btn primary" type="submit">Guardar</button>
          </div>
        </form>
      </div>
    </div>
  </dialog>

  <div class="toast" id="toast">Acción realizada.</div>
  <footer><p>@ Darwin Lucumi 2025</p></footer>

  
 
 <script>
const sel = document.querySelector('select[name="to"]');
const rowStudent = document.getElementById('row-student');
const student = rowStudent ? rowStudent.querySelector('input[name="student"]') : null;
const form = document.getElementById('notifyForm');

function toggleStudent() {
  if (!sel || !rowStudent || !student) return; // Evita error si no existen
  const one = sel.value === 'one';
  rowStudent.hidden = !one;
  student.required = one;
  if (!one) student.value = '';
}

if (sel) sel.addEventListener('change', toggleStudent);
toggleStudent();

// Evento de envío simulado
if (form) {
  form.addEventListener('submit', (e) => {
    e.preventDefault();
    const data = Object.fromEntries(new FormData(form));
    console.log('Datos listos para enviar:', data);
    alert('Simulación: datos listos para backend.\nMira la consola del navegador.');
  });
}

// Mensaje cuando se limpia el formulario de pagos
 const pagoForm = document.getElementById('pagoForm');
if (pagoForm) {
  pagoForm.addEventListener('reset', () => {
    const toast = document.getElementById('toast');
    toast.textContent = 'Formulario limpiado, los pagos anteriores se mantienen.';
    toast.classList.add('show');
    setTimeout(() => toast.classList.remove('show'), 3000);
  });
}
</script>

<script src="app.js"></script>
<script>
document.querySelectorAll(".card").forEach(card => {
  card.addEventListener("mousemove", e => {
    const rect = card.getBoundingClientRect();
    card.style.setProperty("--mx", ((e.clientX - rect.left) / rect.width) * 100 + "%");
    card.style.setProperty("--my", ((e.clientY - rect.top) / rect.height) * 100 + "%");
  });
});
</script>
</body>
</html>






