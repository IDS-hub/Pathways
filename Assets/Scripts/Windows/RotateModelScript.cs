using UnityEngine;
using System.Collections;

public class RotateModelScript : MonoBehaviour {

	public void DoRotate(int rotate){
		float y = transform.rotation.y;
		transform.rotation = Quaternion.Euler(0, y + (360 - rotate), 0);
	}
}
