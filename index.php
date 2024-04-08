<?php

// Função para gerar hash
function gerarHash($url) {
  return md5(uniqid($url, true));
}

// Função para encurtar URL
function encurtarURL($url) {
  $hash = gerarHash($url);
  $db = conectarBanco(); // Conecta ao banco de dados
  $sql = "INSERT INTO urls (url_original, hash) VALUES (?, ?)";
  $stmt = $db->prepare($sql);
  $stmt->execute([$url, $hash]);
  return $hash;
}

// Função para redirecionar para URL original
function redirecionar($hash) {
  $db = conectarBanco(); // Conecta ao banco de dados
  $sql = "SELECT url_original FROM urls WHERE hash = ?";
  $stmt = $db->prepare($sql);
  $stmt->execute([$hash]);
  $url = $stmt->fetchColumn();
  if (!$url) {
    echo "URL não encontrada";
    exit;
  }
  header("Location: $url");
  exit;
}

?>