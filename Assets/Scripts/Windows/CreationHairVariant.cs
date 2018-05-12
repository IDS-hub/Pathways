using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using UnityEngine.UI;

public class CreationHairVariant : MonoBehaviour {

	[SerializeField] CreationHair parentHair;
	[SerializeField] Image hair;

	public void OnClickHair(){
		parentHair.OnClickHairVariant(int.Parse(gameObject.name), hair.sprite);
	}
}
