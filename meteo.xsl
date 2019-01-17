<?xml version="1.0" encoding="utf-8" standalone="no"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
    <xsl:strip-space elements="*"/>
    <xsl:output method="html" version="1.0" encoding="UTF-8" indent="yes"/>
    <xsl:template match='/'>                       
        <div class="meteo_container">                                        
            <xsl:apply-templates select='/previsions/echeance'/>            
        </div>            
    </xsl:template>                    

    <xsl:template match='/previsions/echeance'>  
    <xsl:variable name="limit" select="number(substring(//previsions/echeance[1]/@timestamp,9,2))+2"/>              
        <xsl:if test="$limit>number(substring(@timestamp,9,2))">
            <xsl:if test="not(concat(substring(preceding-sibling::*[1]/@timestamp,9,2),'/',substring(preceding-sibling::*[1]/@timestamp,6,2),'/',substring(preceding-sibling::*[1]/@timestamp,1,4))=concat(substring(@timestamp,9,2),'/',substring(@timestamp,6,2),'/',substring(@timestamp,1,4)))">
                <xsl:element name="div">
                    <xsl:attribute name="class">container</xsl:attribute>
                    <div class="meteo">
                        <xsl:choose>
                            <xsl:when test="sum(//echeance[substring(@timestamp,9,2)=substring(current()/@timestamp,9,2)]/pluie)>0.5">
                                <img src="img/pluie.png" style="width:100px;"/>
                            </xsl:when>
                            <xsl:otherwise>
                                <img src="img/soleil.png" style="width:100px;"/>                                
                            </xsl:otherwise>
                        </xsl:choose>                                 
                    </div>
                    <div class="jour">                        
                        <xsl:value-of select="concat(substring(@timestamp,9,2),'/',substring(@timestamp,6,2),'/',substring(@timestamp,1,4))"/>
                    </div>
                </xsl:element>
            </xsl:if>                            

            <xsl:element name="div">
                <xsl:attribute name="class">info_heure <xsl:value-of select="concat(substring(@timestamp,9,2),'/',substring(@timestamp,6,2),'/',substring(@timestamp,1,4))"/></xsl:attribute>            
                <div class="heure" onclick="revele_info(event)">
                    <xsl:value-of select="substring(@timestamp,12,5)"/>
                </div>
                <div class="info">
                    <div class="temperature">
                        <xsl:if test="temperature/level[@val='2m']">
                            <xsl:variable name='kelvinToCelcius' select='273.15'/>
                            <xsl:variable name='tmp' select='temperature/level[@val="2m"]'/>
                            <xsl:value-of select='round($tmp - $kelvinToCelcius)'/>
                        </xsl:if>
                        <xsl:text> °C</xsl:text>
                    </div>
                    <div class="precipitation">
                        <xsl:value-of select='pluie'/>
                        <xsl:text> mm</xsl:text>
                    </div>
                </div>
            </xsl:element>
        </xsl:if>
    </xsl:template>
</xsl:stylesheet>