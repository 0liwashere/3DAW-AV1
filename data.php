<?php

define('DATA_DIR',      __DIR__ . '/../data/');
define('QUESTIONS_FILE', DATA_DIR . 'perguntas.txt');
define('USERS_FILE',     DATA_DIR . 'usuarios.txt');


function init_storage(): void {
    if (!is_dir(DATA_DIR)) mkdir(DATA_DIR, 0755, true);
    if (!file_exists(QUESTIONS_FILE)) file_put_contents(QUESTIONS_FILE, '');
    if (!file_exists(USERS_FILE))     file_put_contents(USERS_FILE,     '');
}


function generate_id(): string {
    return uniqid('Q', true);
}


function encode_question(array $q): string {
    return implode('|', [
        $q['id'],
        $q['tipo'],
        base64_encode($q['enunciado']),
        base64_encode(json_encode($q['respostas'])),
    ]) . "\n";
}

function decode_question(string $line): ?array {
    $line = trim($line);
    if ($line === '') return null;
    $parts = explode('|', $line, 4);
    if (count($parts) < 4) return null;
    return [
        'id'        => $parts[0],
        'tipo'      => $parts[1],
        'enunciado' => base64_decode($parts[2]),
        'respostas' => json_decode(base64_decode($parts[3]), true) ?? [],
    ];
}

// ── CRUD ─────────────────────────────────────

function get_all_questions(): array {
    init_storage();
    $lines = file(QUESTIONS_FILE, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $questions = [];
    foreach ($lines as $line) {
        $q = decode_question($line);
        if ($q) $questions[] = $q;
    }
    return $questions;
}

function get_question_by_id(string $id): ?array {
    foreach (get_all_questions() as $q) {
        if ($q['id'] === $id) return $q;
    }
    return null;
}

function save_question(array $question): bool {
    init_storage();
    $all = get_all_questions();
    $all[] = $question;
    return write_all($all);
}

function update_question(array $updated): bool {
    $all = get_all_questions();
    $found = false;
    foreach ($all as &$q) {
        if ($q['id'] === $updated['id']) {
            $q = $updated;
            $found = true;
            break;
        }
    }
    unset($q);
    if (!$found) return false;
    return write_all($all);
}

function delete_question(string $id): bool {
    $all = get_all_questions();
    $filtered = array_filter($all, fn($q) => $q['id'] !== $id);
    if (count($filtered) === count($all)) return false;
    return write_all(array_values($filtered));
}

function write_all(array $questions): bool {
    $content = '';
    foreach ($questions as $q) $content .= encode_question($q);
    return file_put_contents(QUESTIONS_FILE, $content) !== false;
}

function get_all_users(): array {
    init_storage();
    $lines = file(USERS_FILE, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $users = [];
    foreach ($lines as $line) {
        $parts = explode('|', $line, 3);
        if (count($parts) >= 2) {
            $users[] = ['nome' => base64_decode($parts[0]), 'email' => base64_decode($parts[1])];
        }
    }
    return $users;
}

function save_user(string $nome, string $email): bool {
    init_storage();
    $line = base64_encode($nome) . '|' . base64_encode($email) . "\n";
    return file_put_contents(USERS_FILE, $line, FILE_APPEND) !== false;
}
