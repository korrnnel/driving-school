@if(Auth::check() && Auth::user()->role === 'student')
    <div class="modal fade" id="enrollModal" tabindex="-1" aria-labelledby="enrollModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('student.enrollments.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="course_id" id="modal_course_id">

                    <div class="modal-header">
                        <h5 class="modal-title fw-bold" id="enrollModalLabel">Zapis na kurs</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Zamknij"></button>
                    </div>

                    <div class="modal-body">
                        <p class="mb-3">
                            Składasz zgłoszenie na kurs: <strong id="modal_course_name"></strong>
                        </p>

                        <div class="mb-3">
                            <label for="modal_pkk_number" class="form-label">Numer PKK (opcjonalnie)</label>
                            <input type="text" class="form-control" id="modal_pkk_number" name="pkk_number" maxlength="20" placeholder="20-cyfrowy numer PKK">
                            <div class="form-text">Jeśli jeszcze nie masz numeru PKK, zostaw to pole puste i dostarcz go później do biura.</div>
                        </div>

                        <div class="alert alert-info small mb-0">
                            Po wysłaniu zgłoszenia otrzyma ono status <strong>Oczekuje</strong> do czasu weryfikacji przez biuro OSK.
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Anuluj</button>
                        <button type="submit" class="btn btn-primary">Wyślij zgłoszenie</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const enrollModal = document.getElementById('enrollModal');
            if (enrollModal) {
                enrollModal.addEventListener('show.bs.modal', function (event) {
                    const button = event.relatedTarget;
                    document.getElementById('modal_course_id').value = button.getAttribute('data-course-id');
                    document.getElementById('modal_course_name').textContent = button.getAttribute('data-course-name');
                });
            }
        });
    </script>
@endif
