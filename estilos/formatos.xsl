<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

  <xsl:template match="/">
    <html>
      <head>
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

          h2 {
            color: #800000;
          }
        </style>
      </head>
      <body>
        <img src="imagenes/encabe.png" alt="">
        <h2>Películas Más Solicitadas</h2>
        
        <table>
          <tr>
            <th>Código del Filme</th>
            <th>Nombre del Filme</th>
            <th>Veces Alquilada</th>
            <th>Total Generado</th>
          </tr>
          
          <xsl:for-each select="peliculas_solicitadas/pelicula">
            <tr>
              <td><xsl:value-of select="codigo_pelicula"/></td>
              <td><xsl:value-of select="nombre_pelicula"/></td>
              <td><xsl:value-of select="veces_alquilada"/></td>
              <td><xsl:value-of select="total_generado"/></td>
            </tr>
          </xsl:for-each>
          
          <tr>
            <th colspan="3">Gran Total Generado</th>
            <td><xsl:value-of select="peliculas_solicitadas/gran_total_generado"/></td>
          </tr>
        </table>
      </body>
    </html>
  </xsl:template>

</xsl:stylesheet>
