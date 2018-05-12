using UnityEngine;
using System.Collections;
using System.Collections.Generic;

public class AttachClothes : MonoBehaviour {

	public List<SkinnedMeshRenderer> skinnedMeshRendererList = new List<SkinnedMeshRenderer>();

	// attach hair with Model
	public void AttachBones(Transform[] bones, Transform parent, Transform rootBone, SkinnedMeshRenderer parentMesh) {
		transform.parent = parent;
		transform.localPosition = Vector3.zero;
		for (int k = 0; k < skinnedMeshRendererList.Count; k++) {
			Transform[] newBones = new Transform[skinnedMeshRendererList[k].bones.Length];

			for (int i = 0; i < skinnedMeshRendererList[k].bones.Length; ++i) {
				string name = skinnedMeshRendererList[k].bones[i].gameObject.name;
				for (int j = 0; j < bones.Length; j++) {
					if (bones[j].name == name) {
						newBones[i] = bones[j];
						break;
					}
				}
			}
			Destroy(skinnedMeshRendererList[k].rootBone.gameObject);
			skinnedMeshRendererList[k].bones = newBones;
			skinnedMeshRendererList[k].rootBone = rootBone;
		}

		if (parentMesh != null) {
			SetBlendShapeToClothes(parentMesh);
		}
	}

	void Remove() {
		Destroy(gameObject);
	}

	public void changeBlendShapeTshirt(int ind_, float blendVal) {
		for (int k = 0; k < skinnedMeshRendererList.Count; k++) {
			if (skinnedMeshRendererList[k].sharedMesh.blendShapeCount > ind_)
				skinnedMeshRendererList[k].SetBlendShapeWeight(ind_, blendVal);	
		}
	}

	private void SetBlendShapeToClothes(SkinnedMeshRenderer parentMesh) {
		for (int k = 0; k < skinnedMeshRendererList.Count; k++) {
			for (int jj = 0; jj < parentMesh.sharedMesh.blendShapeCount; jj++) {
				if (jj < skinnedMeshRendererList[k].sharedMesh.blendShapeCount)
					skinnedMeshRendererList[k].SetBlendShapeWeight(jj, parentMesh.GetBlendShapeWeight(jj));
			}
		}
	}
}
