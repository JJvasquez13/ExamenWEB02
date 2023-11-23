<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

<xsl:template match="/">
  <html>
  <head>
    <meta charset="utf-8"/>
    <title>Películas con Actores</title>
    <style>
      body {
        background-color: #FFFFCC;
        color: #800000;
      }
      table {
        border-collapse: collapse;
        width: 100%;
      }
      th, td {
        border: 1px solid #800000;
        padding: 8px;
        text-align: left;
      }
      th {
        background-color: #800000;
        color: #FFFFCC;
      }
      h2 {
        color: #800000;
      }
    </style>
  </head>
  <body>
    <img src="imagenes/encabe.png" alt="">
    <h2>Películas con Actores</h2>
    <xsl:apply-templates select="peliculas_con_actores/pelicula"/>
  </body>
  </html>
</xsl:template>

<xsl:template match="pelicula">
  <xsl:variable name="codigo_pelicula" select="codigo_pelicula"/>
  <xsl:variable name="nombre_pelicula" select="nombre_pelicula"/>
  <xsl:variable name="descripcion" select="descripcion"/>
  <xsl:variable name="categorias" select="categorias"/>
  <xsl:variable name="anno" select="anno"/>
  
  <table border="1">
    <tr>
      <th>Código de la Película</th>
      <th>Nombre de la Película</th>
      <th>Descripción</th>
      <th>Categorías</th>
      <th>Año</th>
    </tr>
    <tr>
      <td><xsl:value-of select="$codigo_pelicula"/></td>
      <td><xsl:value-of select="$nombre_pelicula"/></td>
      <td><xsl:value-of select="$descripcion"/></td>
      <td><xsl:value-of select="$categorias"/></td>
      <td><xsl:value-of select="$anno"/></td>
    </tr>
  </table>

  <br/>

  <h3>Lista de Actores:</h3>
  <xsl:apply-templates select="actores/actor"/>
  <br/><br/>
</xsl:template>

<xsl:template match="actor">
  <xsl:value-of select="."/><br/>
</xsl:template>

</xsl:stylesheet>
