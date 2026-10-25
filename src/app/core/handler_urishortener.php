<?php

class UriShortenerHandler {
    public function __construct(private PDO $db) {
    }

    public function shortenUrl(string $originalUrl, string $customAlias = ""): array {
        $originalUrl = trim($originalUrl);
        $customAlias = trim($customAlias);

        if (!filter_var($originalUrl, FILTER_VALIDATE_URL)
            || !in_array(strtolower((string) parse_url($originalUrl, PHP_URL_SCHEME)), ["http", "https"], true)) {
            return ["success" => false, "message" => "Masukkan URL lengkap yang diawali http:// atau https://."];
        }

        if ($customAlias !== "" && !preg_match('/\A[a-zA-Z0-9_-]{3,32}\z/', $customAlias)) {
            return ["success" => false, "message" => "Alias harus terdiri dari 3–32 karakter: huruf, angka, tanda hubung, atau garis bawah."];
        }

        for ($attempt = 0; $attempt < 5; $attempt++) {
            $shortenedUrl = $customAlias !== ""
                ? $customAlias
                : bin2hex(random_bytes(3));

            try {
                $statement = $this->db->prepare(
                    "INSERT INTO url_shortener (original_url, shortened_url) VALUES (:original_url, :shortened_url)"
                );
                $statement->execute([
                    ":original_url" => $originalUrl,
                    ":shortened_url" => $shortenedUrl,
                ]);

                return ["success" => true, "shortened_url" => $shortenedUrl];
            } catch (PDOException $exception) {
                if ($exception->getCode() !== "23000") {
                    throw $exception;
                }

                if ($customAlias !== "") {
                    return ["success" => false, "message" => "Alias tersebut sudah dipakai. Coba alias lain."];
                }
            }
        }

        throw new RuntimeException("Unable to generate a unique shortened URL.");
    }

    public function getOriginalUrl(string $shortenedUrl): ?string {
        $statement = $this->db->prepare(
            "SELECT original_url FROM url_shortener WHERE shortened_url = :shortened_url"
        );
        $statement->execute([":shortened_url" => $shortenedUrl]);
        $originalUrl = $statement->fetchColumn();

        return is_string($originalUrl) ? $originalUrl : null;
    }
}
