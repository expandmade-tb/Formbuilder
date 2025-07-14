class Datepicker {
  static activePicker = null;

  constructor(selector, format = 'yyyy-mm-dd', lang = 'en') {
    const validFormats = ['dd.mm.yyyy', 'dd/mm/yyyy', 'mm/dd/yyyy', 'yyyy-mm-dd', 'Y-m-d'];
    if (!validFormats.includes(format)) {
      throw new Error(`Invalid date format: ${format}. Supported formats are: ${validFormats.join(', ')}`);
    }

    this.input = document.querySelector(selector);
    if (!this.input) {
      throw new Error(`No element found with selector: ${selector}`);
    }

    const validLangs = ['de', 'en', 'es'];
    if (!validLangs.includes(lang)) {
      console.warn(`Invalid language: ${lang}. Defaulting to 'en'.`);
      lang = 'en';
    }

    this.format = format;
    this.lang = lang;
    this.calendar = null;
    this.isNavigatingCalendar = false;

    const today = new Date();
    this.currentYear = today.getFullYear();
    this.currentMonth = today.getMonth();

    this.init();
  }

  init() {
    this.input.addEventListener('focus', () => this.showCalendar());

    this.input.addEventListener('blur', (e) => {
      setTimeout(() => {
        if (!this.calendar.contains(document.activeElement) && !this.isNavigatingCalendar) {
          this.hideCalendar();
        }
      }, 200);
    });

    document.addEventListener('mousedown', (e) => {
      if (this.calendar && !this.calendar.contains(e.target) && e.target !== this.input) {
        this.hideCalendar();
      }
    });

    document.addEventListener('keydown', (e) => {
      if (e.key === "Escape") {
        this.hideCalendar();
      }
    });
  }

  showCalendar() {
    if (Datepicker.activePicker && Datepicker.activePicker !== this) {
      Datepicker.activePicker.hideCalendar();
    }

    Datepicker.activePicker = this;

    if (!this.calendar) {
      this.calendar = document.createElement('div');
      this.calendar.className = 'datepicker-calendar';
      this.calendar.tabIndex = -1;
      this.calendar.style.position = 'absolute';
      document.body.appendChild(this.calendar);
    }

    const selectedDate = this.parseDate(this.input.value);
    if (selectedDate) {
      this.currentYear = selectedDate.getFullYear();
      this.currentMonth = selectedDate.getMonth();
    } else {
      const today = new Date();
      this.currentYear = today.getFullYear();
      this.currentMonth = today.getMonth();
    }

    this.renderCalendar();

    this.calendar.style.visibility = 'hidden';
    this.calendar.style.display = 'block';

    const rect = this.input.getBoundingClientRect();
    const calendarRect = this.calendar.getBoundingClientRect();

    const viewportWidth = window.innerWidth;
    const viewportHeight = window.innerHeight;

    let top = rect.bottom + window.scrollY;
    let left = rect.left + window.scrollX;

    const spaceBelow = viewportHeight - rect.bottom;
    const spaceAbove = rect.top;
    const spaceRight = viewportWidth - rect.left;
    const spaceLeft = rect.right;

    if (spaceBelow < calendarRect.height && spaceAbove > calendarRect.height) {
      top = rect.top + window.scrollY - calendarRect.height;
    }

    if (spaceRight < calendarRect.width && spaceLeft > calendarRect.width) {
      left = rect.right + window.scrollX - calendarRect.width;
    }

    this.calendar.style.top = `${top}px`;
    this.calendar.style.left = `${left}px`;
    this.calendar.style.visibility = '';
  }

  hideCalendar() {
    if (this.calendar) this.calendar.style.display = 'none';
  }

  renderCalendar() {
    const selectedDate = this.parseDate(this.input.value);
    const date = selectedDate || new Date();

    const year = this.currentYear;
    const month = this.currentMonth;

    const firstDay = new Date(year, month, 1).getDay();
    const daysInMonth = new Date(year, month + 1, 0).getDate();

    const months = {
      de: ['Januar','Februar','März','April','Mai','Juni','Juli','August','September','Oktober','November','Dezember'],
      en: ['January','February','March','April','May','June','July','August','September','October','November','December'],
      es: ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre']
    }[this.lang];

    const weekdays = {
      de: ['So','Mo','Di','Mi','Do','Fr','Sa'],
      en: ['Su','Mo','Tu','We','Th','Fr','Sa'],
      es: ['Do','Lu','Ma','Mi','Ju','Vi','Sa']
    }[this.lang];

    let html = `
      <div class="datepicker-nav">
        <button class="prev-month">&#8592;</button>
        <span>${months[month]} ${year}</span>
        <button class="next-month">&#8594;</button>
      </div>
      <div class="datepicker-weekdays">${weekdays.map(d => `<span>${d}</span>`).join('')}</div>
      <div class="datepicker-grid">
    `;

    for (let i = 0; i < firstDay; i++) html += '<span></span>';
    for (let i = 1; i <= daysInMonth; i++) {
      const currentDay = new Date(year, month, i);
      const isSelected = selectedDate &&
        selectedDate.getDate() === currentDay.getDate() &&
        selectedDate.getMonth() === currentDay.getMonth() &&
        selectedDate.getFullYear() === currentDay.getFullYear();

      html += `<button data-day="${i}" class="${isSelected ? 'selected' : ''}">${i}</button>`;
    }

    html += '</div>';
    html += `<div class="datepicker-footer"><button class="today-btn">${this.lang === 'de' ? 'Heute' : this.lang === 'es' ? 'Hoy' : 'Today'}</button></div>`;

    this.calendar.innerHTML = html;

    this.calendar.querySelector('.prev-month').onclick = () => {
      this.isNavigatingCalendar = true;
      this.currentMonth--;
      if (this.currentMonth < 0) {
        this.currentMonth = 11;
        this.currentYear--;
      }
      this.renderCalendar();
    };

    this.calendar.querySelector('.next-month').onclick = () => {
      this.isNavigatingCalendar = true;
      this.currentMonth++;
      if (this.currentMonth > 11) {
        this.currentMonth = 0;
        this.currentYear++;
      }
      this.renderCalendar();
    };

    this.calendar.querySelectorAll('[data-day]').forEach(btn => {
      btn.onclick = () => {
        const day = +btn.dataset.day;
        const selected = new Date(this.currentYear, this.currentMonth, day);
        this.input.value = this.formatDate(selected);
        this.hideCalendar();
      };
    });

    this.calendar.querySelector('.today-btn').onclick = () => {
      const today = new Date();
      this.input.value = this.formatDate(today);
      this.currentYear = today.getFullYear();
      this.currentMonth = today.getMonth();
      this.hideCalendar();
    };
  }

  parseDate(str) {
    if (!str) return null;

    const delimiterMatch = str.match(/[-\/.]/);
    if (!delimiterMatch) return null;

    const delimiter = delimiterMatch[0];
    const parts = str.split(delimiter).map(Number);

    if (parts.length !== 3 || parts.some(isNaN)) return null;

    let day, month, year;

    switch (this.format) {
      case 'dd.mm.yyyy':
      case 'dd/mm/yyyy':
        [day, month, year] = parts;
        break;
      case 'mm/dd/yyyy':
        [month, day, year] = parts;
        break;
      case 'yyyy-mm-dd':
      case 'Y-m-d':
        [year, month, day] = parts;
        break;
      default:
        return null;
    }

    const date = new Date(year, month - 1, day);

    if (
      date.getFullYear() !== year ||
      date.getMonth() !== month - 1 ||
      date.getDate() !== day
    ) {
      return null;
    }

    return date;
  }

  formatDate(date) {
    const pad = n => n < 10 ? '0' + n : n;

    const day = pad(date.getDate());
    const month = pad(date.getMonth() + 1);
    const year = date.getFullYear();

    switch (this.format) {
      case 'dd.mm.yyyy':
        return `${day}.${month}.${year}`;
      case 'dd/mm/yyyy':
        return `${day}/${month}/${year}`;
      case 'mm/dd/yyyy':
        return `${month}/${day}/${year}`;
      case 'yyyy-mm-dd':
      case 'Y-m-d':
        return `${year}-${month}-${day}`;
      default:
        return date.toDateString();
    }
  }
}