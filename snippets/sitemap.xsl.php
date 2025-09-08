<?php header('Content-Type: application/xslt+xml; charset=utf-8'); ?>
<xsl:stylesheet version="1.0"
  xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
  xmlns:s="http://www.sitemaps.org/schemas/sitemap/0.9"
  exclude-result-prefixes="s">

  <xsl:output method="html" encoding="utf-8"/>

  <xsl:template match="/">
    <html>
      <head>
        <meta charset="utf-8"/>
        <title>Sitemap</title>
        <style>
          body{font:14px/1.5 system-ui,-apple-system,Segoe UI,Roboto}
          table{border-collapse:collapse;width:100%}
          th,td{border:1px solid #ddd;padding:8px;text-align:left;vertical-align:top}
          th{background:#f6f6f6}
          a{word-break:break-all}
        </style>
      </head>
      <body>
        <h1>Sitemap</h1>
        <table>
          <thead>
            <tr><th>URL</th><th>Last Modified</th><th>Priority</th></tr>
          </thead>
          <tbody>
            <!-- note the s: prefix everywhere -->
            <xsl:for-each select="/s:urlset/s:url">
              <tr>
                <td><a href="{s:loc}"><xsl:value-of select="s:loc"/></a></td>
                <td><xsl:value-of select="s:lastmod"/></td>
                <td><xsl:value-of select="s:priority"/></td>
              </tr>
            </xsl:for-each>
          </tbody>
        </table>
      </body>
    </html>
  </xsl:template>
</xsl:stylesheet>
