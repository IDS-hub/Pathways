// Upgrade NOTE: replaced 'mul(UNITY_MATRIX_MVP,*)' with 'UnityObjectToClipPos(*)'

// Unity built-in shader source. Copyright (c) 2016 Unity Technologies. MIT license (see license.txt)

Shader "Custome Shaders/Transparent/Diffuse" {
Properties {
	_Color ("Main Color", Color) = (1,1,1,1)
	_MainTex ("Base (RGB) Trans (A)", 2D) = "white" {}
}

SubShader {
	Tags {"Queue"="Transparent" "IgnoreProjector"="False" "RenderType"="Transparent"}
	LOD 200
    ZWrite off
    ColorMask RGBA
    Blend Off


         CGPROGRAM
         #pragma vertex vert
         #pragma fragment frag
 
         fixed4 _Color;
         sampler2D _GrabTexture;
         struct appdata
         {
             float4 vertex : POSITION;
         };
         struct v2f
         {
             float4 pos : SV_POSITION;
             float4 uv : TEXCOORD0;
         };
         v2f vert (appdata v)
         {
             v2f o;
             o.pos = UnityObjectToClipPos(v.vertex);
             o.uv = o.pos;
             return o;
         }
         half4 frag(v2f i) : COLOR
         {
             float2 coord = 0.5 + 0.5 * i.uv.xy / i.uv.w;
             fixed4 tex = tex2D(_GrabTexture, float2(coord.x, 1 - coord.y));
             return fixed4(lerp(tex.rgb, _Color.rgb, _Color.a), 1);
         }
         ENDCG 
   
          
}

Fallback "Legacy Shaders/Transparent/VertexLit"
}
