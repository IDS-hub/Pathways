/// Credit CiaccoDavide
/// Sourced from - http://ciaccodavi.de/unity/uipolygon

using System.Collections.Generic;

namespace UnityEngine.UI.Extensions {
	[AddComponentMenu("UI/Extensions/Primitives/UI Polygon")]
	public class UIPolygon : MaskableGraphic {
		[SerializeField]
		Texture m_Texture;

		bool fill = true;
		float thickness = 5;
		int sides = 4;
		float rotation = 0;
		float[] VerticesDistances = new float[4];

		[SerializeField] float size = 0;

		[SerializeField] Vector2 pos0, pos1, pos2, pos3;

		public override Texture mainTexture {
			get {
				return m_Texture == null ? s_WhiteTexture : m_Texture;
			}
		}

		public Texture texture {
			get {
				return m_Texture;
			}
			set {
				if (m_Texture == value)
					return;
				m_Texture = value;
				SetVerticesDirty();
				SetMaterialDirty();
			}
		}

		/*	public void DrawPolygon(int _sides) {
			sides = _sides;
			VerticesDistances = new float[_sides + 1];
			for (int i = 0; i < _sides; i++)
				VerticesDistances[i] = 1;
			;
			rotation = 0;
		}

		public void DrawPolygon(int _sides, float[] _VerticesDistances) {
			sides = _sides;
			VerticesDistances = _VerticesDistances;
			rotation = 0;
		}*/

		public void SetPosition(Vector2 pos0, Vector2 pos1, Vector2 pos2, Vector2 pos3) {
			//this.pos0 = pos0;
			//this.pos1 = pos1;
			//this.pos2 = pos2;
			//this.pos3 = pos3;
		}

		public void SetPoint(Vector2 start, Vector2 end) {
			//Debug.Log(string.Format("start {0} and end {1}", start, end));
			//pos3 = start;
			//pos2 = end;
			//pos0 = new Vector2(start.x, start.y - 800);
			//pos1 = new Vector2(end.x, end.y - 800);

		}

		/*	public void DrawPolygon(int _sides, float[] _VerticesDistances, float _rotation) {
			sides = _sides;
			VerticesDistances = _VerticesDistances;
			rotation = _rotation;
		}*/

		void Update() {
			/*	size = rectTransform.rect.width;
			if (rectTransform.rect.width > rectTransform.rect.height)
				size = rectTransform.rect.height;
			else
				size = rectTransform.rect.width;
			thickness = (float)Mathf.Clamp(thickness, 0, size / 2);*/
		}

		public void SetAngle(float angle) {
			rotation = angle;
		}

		protected UIVertex[] SetVbo(Vector2[] vertices, Vector2[] uvs) {
			Debug.Log("SetVbo");

			size = rectTransform.rect.width;
			Debug.Log("rect " + rectTransform.rect);
			Debug.Log(string.Format("min {0}", rectTransform.rect.min));
			Debug.Log(string.Format("max {0}", rectTransform.rect.max));

			Vector2 max = rectTransform.rect.max;
			Vector2 min = rectTransform.rect.min;

			pos2 = new Vector2(max.x, max.y);
			pos3 = new Vector2(min.x, max.y);

			pos0 = Quaternion.Euler(0, 0, rotation) * pos3;
			pos0.y = max.y - 500;


			pos1 = Quaternion.Euler(0, 0, 90 + rotation) * pos0 * Vector2.Distance(pos2, pos3);

			UIVertex[] vbo = new UIVertex[4];
			for (int i = 0; i < vertices.Length; i++) {
				var vert = UIVertex.simpleVert;
				vert.color = color;
				vert.position = vertices[i];
				vert.uv0 = uvs[i];
				vbo[i] = vert;
			}
			return vbo;
		}

		protected override void OnPopulateMesh(VertexHelper vh) {
			Debug.Log("OnPopulateMesh");
			vh.Clear();
			//Vector2 prevX = Vector2.zero;
			//Vector2 prevY = Vector2.zero;
			Vector2 uv0 = new Vector2(0, 0);
			Vector2 uv1 = new Vector2(0, 1);
			Vector2 uv2 = new Vector2(1, 1);
			Vector2 uv3 = new Vector2(1, 0);
			/*   Vector2 pos0;
            Vector2 pos1;
            Vector2 pos2;
            Vector2 pos3;*/
			float degrees = 360f / sides;
			int vertices = sides + 1;
			if (VerticesDistances.Length != vertices) {
				VerticesDistances = new float[vertices];
				for (int i = 0; i < vertices - 1; i++)
					VerticesDistances[i] = 1;
			}
			// last vertex is also the first!
			VerticesDistances[vertices - 1] = VerticesDistances[0];
			for (int i = 0; i < vertices; i++) {
				//float outer = -rectTransform.pivot.x * size * VerticesDistances[i];
				//float inner = -rectTransform.pivot.x * size * VerticesDistances[i] + thickness;
				//float rad = Mathf.Deg2Rad * (i * degrees + rotation);
				//float c = Mathf.Cos(rad);
				//float s = Mathf.Sin(rad);
				uv0 = new Vector2(0, 1);
				uv1 = new Vector2(1, 1);
				uv2 = new Vector2(1, 0);
				uv3 = new Vector2(0, 0);

				/*        pos0 = prevX;
                pos1 = new Vector2(outer * c, outer * s);
                if (fill)
                {
                    pos2 = Vector2.zero;
                    pos3 = Vector2.zero;
                }
                else
                {
                    pos2 = new Vector2(inner * c, inner * s);
                    pos3 = prevY;
                }
                prevX = pos1;
                prevY = pos2;*/

				vh.AddUIVertexQuad(SetVbo(new[] { pos0, pos1, pos2, pos3 }, new[] { uv0, uv1, uv2, uv3 }));
			}
		}
	}
}
