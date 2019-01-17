<?xml version="1.0" encoding="utf-8" standalone="no"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
    <xsl:strip-space elements="*"/>
    <xsl:output method="html" version="1.0" encoding="UTF-8" indent="yes"/>
    <xsl:template match='/'>
        <html>
            <head>
                <link rel="stylesheet" href="style.css"/>
            </head>
            <body>
                <div id="title_container">
                    <div id="title">
                        <h1>Meteo sur une semaine</h1>
                    </div>
                    <div id="example">
                        <div>Date</div>
                        <div>Heure</div>
                        <div>Température</div>
                        <div>Précipitations</div>
                    </div>
                </div>
                <div id="meteo_container">
                    <xsl:apply-templates select='/previsions/echeance'/>
                </div>
            </body>
        </html>
    </xsl:template>
    <xsl:template match='/previsions/echeance'>
        <div id="date">
            <div id="jour">
                <xsl:value-of select="concat(substring(@timestamp,9,2),'/',substring(@timestamp,6,2),'/',substring(@timestamp,1,4))"/>
            </div>
            <div id="heure">
                <xsl:value-of select="substring(@timestamp,12,5)"/>
            </div>
            <div id="temperature">
                <xsl:variable name='kelvinToCelcius' select='273.15'>
                </xsl:variable>
                <xsl:variable name='temperature' select='temperature/level[@val="2m"]'>
                </xsl:variable>
                <xsl:value-of select='round($temperature - $kelvinToCelcius)'/>
                <xsl:text> °C</xsl:text>
            </div>
            <div id="precipitation">
                <xsl:value-of select='pluie'/>
                <xsl:text> mm</xsl:text>
            </div>
        </div>

    </xsl:template>
</xsl:stylesheet>