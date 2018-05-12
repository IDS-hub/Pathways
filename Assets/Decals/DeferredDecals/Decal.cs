using UnityEngine;
using DG.Tweening;

[ExecuteInEditMode]
public class Decal : MonoBehaviour {
	public enum Kind {
		DiffuseOnly,
		NormalsOnly,
		Both
	}

	public Kind m_Kind;
	public Material m_Material;

	Color startColor;

	public void OnEnable() {
		DeferredDecalSystem.instance.AddDecal(this);
		SessionWork2.ShowDecalAnim += SessionWork2DecalAnim;
		SessionWork12.ShowDecalAnim += SessionWork12DecalAnim;
		SessionWork20.ShowDecalAnim += SessionWork20DecalAnim;
	}

	public void Start() {
		DeferredDecalSystem.instance.AddDecal(this);
		m_Material.DOKill();
		startColor = m_Material.color;
	}

	public void OnDisable() {
		DeferredDecalSystem.instance.RemoveDecal(this);
		SessionWork2.ShowDecalAnim -= SessionWork2DecalAnim;
		SessionWork12.ShowDecalAnim -= SessionWork12DecalAnim;
		SessionWork20.ShowDecalAnim += SessionWork20DecalAnim;
	}

	private void DrawGizmo(bool selected) {
		var col = new Color(0.0f, 0.7f, 1f, 1.0f);
		col.a = selected ? 0.3f : 0.1f;
		Gizmos.color = col;
		Gizmos.matrix = transform.localToWorldMatrix;
		Gizmos.DrawCube(Vector3.zero, Vector3.one);
		col.a = selected ? 0.5f : 0.2f;
		Gizmos.color = col;
		Gizmos.DrawWireCube(Vector3.zero, Vector3.one);		
	}

	public void OnDrawGizmos() {
		DrawGizmo(false);
	}

	public void OnDrawGizmosSelected() {
		DrawGizmo(true);
	}

	void SessionWork2DecalAnim(bool show, float time) {
		if (show)
			m_Material.DOFade(0f, time);
		else {
			m_Material.DOKill();
			m_Material.color = startColor;
		}
	}

	void SessionWork12DecalAnim(bool show, float time) {
		if (show)
			m_Material.DOColor(new Color(.45f, .28f, 0f), time / 3).OnComplete(() => {
				m_Material.DOColor(Color.grey, time / 3).OnComplete(() => {
					m_Material.DOFade(0f, time / 3);
				});
			});
		else {
			m_Material.DOKill();
			m_Material.color = startColor;
		}
	}

	void SessionWork20DecalAnim(bool show, float time) {
		if (show)
			m_Material.DOColor(Color.grey, time / 3).OnComplete(() => {
				m_Material.DOColor(new Color(.45f, .28f, 0f), time / 3).OnComplete(() => {
					m_Material.DOFade(0f, time / 3);
				});
			});
		else {
			m_Material.DOKill();
			m_Material.color = startColor;
		}
	}
}
