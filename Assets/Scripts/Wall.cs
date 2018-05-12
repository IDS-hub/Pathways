using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using DG.Tweening;

public class Wall : MonoBehaviour {

	[SerializeField] MeshRenderer mesh;

	void OnEnable() {
		Character.HideCharacter += DoHide;
	}

	void OnDisable() {
		Character.HideCharacter -= DoHide;
	}

	void DoHide(bool doHide, bool isAnim) {
		if (isAnim) {
			mesh.enabled = true;

			Color color = mesh.material.color;
			color.a = doHide ? 0 : 1;
			mesh.material.color = color;

			mesh.material.DOFade(doHide ? 1 : 0, 1f).OnComplete(() => {
				//mesh.enabled = false;
				if (Character.HideCharacter != null)
					Character.HideCharacter(doHide, false);
			});

		} else {
			mesh.material.DOKill();
			mesh.enabled = false;
		}
	}
}
