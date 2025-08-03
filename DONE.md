Ad 1. CRUD oprogramowany za pomocą:
- standardowego kontrolera ```app/Http/Controllers/TaskController.php```,
- klasy encji modelu Eloquent ORM ```app/Models/Task.php```,
- oraz widoków Blade znajdujących się w katalogu ```resources/views/tasks```.

Ad 2. Filtrowanie zadań zrealizowane za pomocą pól formularza typu ```select``` oraz ```date```, znajdujących się nad listą (tabelą) zadań oraz parametrów zapytania URL (ang. query params).

Ad 3. Wykorzystano sugerowane mechanizmy framework-a Laravel tj. kolejkowanie zadań (ang. queues) oraz tzw. scheduler-a.

Dla prostoty rozwiązanie do kolejkowania wykorzystano standardowy mechanizm Laravel-a (oczywiście można było do tego celu użyć np. Redis-a, RabbitMQ lub czegokolwiek innego).

Do zasymulowania wysyłki maila z powiadomieniem wykorzystano plik log-a.

Uruchomienie Scheduler-a

```
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

Zarządzanie zadaniami (ang. jobs) wysyłki powiadomień dotyczących poszczególnych zadań (ang. tasks) realizowane w dedykowamej klasie serwisu ```TaskEmailReminderService```.

Ad 4. Reguły walidacji formularzy, służących do tworzenia i edycji zadań, znajduje się odpowiednio w klasach ```app/Http/Requests/StoreTaskRequest.php``` oraz ```app/Http/Requests/UpdateTaskRequest.php```.

Ad 5. Uwierzytelnianie użytkowników zrealizowane zgodnie z sugestią, zawartą w treści tego podpunktu.

Ad 6. Funkcjonalność udostępniona pod przyciskiem znajdującym się w widoku szczegółów zadania.
Czas działania tokena zhardkodowany na 15 minut.
Do przechowywania wygenerowanych tokenów o długości 64 znaków utworzono bazodanową tabelę o nazwie ```access_tokens```.

Ad 7. Historia zmian poszczególnych zadań zrealizowana za pomocą pakietu ```laravel-auditing```.
W przypadku encji ```Task``` logowane są wyłącznie zdarzenia (ang. events) typu ```update```.
Lista zmian wyświetlana jest w widoku szczegółów zadania.

Ad 8. Integracja z usługą Google Calendar zrealizowana zgodnie z sugestią, zawartą w treści tego podpunktu.
