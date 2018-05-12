using UnityEngine;
using System.Collections;
using System.Collections.Generic;
using Lean.Touch;
using System;

[AddComponentMenu("Camera-Control/Mouse Orbit with zoom")]
public class MouseOrbitImproved : MonoBehaviour {
	
	public static Action<Vector3> OnTouchModel;
	public static Action ResetCamera;

	[SerializeField] Transform target;

	[SerializeField] float distance = 5.0f;
	[SerializeField] float xSpeed = 120.0f;
	[SerializeField] float ySpeed = 120.0f;

	[SerializeField] float yMinLimit = -20f;
	[SerializeField] float yMaxLimit = 80f;

	[SerializeField] float offsetY = .2f;

	//[SerializeField] float distanceMin = .5f;
	//[SerializeField] float distanceMax = 15f;

	[SerializeField] float tapTime = 0.2f;

	float x = 0.0f;
	float y = 0.0f;

	bool shouldCameraMove = false;
	bool tapping = false;
	float LastTap;
	bool tapOnModel = false;

	Vector3 initPosition;
	Quaternion initRotation;
	bool dontTouchInput = false;
	bool _isPositionChanging = false;

	void OnEnable() {
		PainSelector.OnPainSelector += ShouldCameraMove;
		PainSelector.OnResetCamera += OnResetCamera;
		CreationTab.OnClick += OnClickAvatarEditTab;

		initPosition = transform.position;
		initRotation = transform.rotation;
	}

	void OnDisable() {
		PainSelector.OnPainSelector -= ShouldCameraMove;
		PainSelector.OnResetCamera -= OnResetCamera;
		CreationTab.OnClick -= OnClickAvatarEditTab;
	}

	// on tab click from Avatar Customisation, camera needs to reposition
	void OnClickAvatarEditTab(CreationTab.TAB tab) {
		if (tab == CreationTab.TAB.Eyes || tab == CreationTab.TAB.Hairstyle || tab == CreationTab.TAB.Face || tab == CreationTab.TAB.Lips || tab == CreationTab.TAB.Nose) {
			_isPositionChanging = true;
			target = UserInfo.HeadEditTargetBone;
			
		} else {
			_isPositionChanging = true;
			target = UserInfo.AvatarTempHip;
		}
	}

	// camera only moves on specific screens
	void ShouldCameraMove(bool move, WindowPanels screen) {
		if (move) {
			/*if (UserInfo.UserOriginalDiagnosis != null && UserInfo.UserOriginalDiagnosis.Count == 1 && screen == WindowPanels.PainSelector) {
				target = UserInfo.TargetBone;

				if (target == null) {
					target = UserInfo.AvatarTempHip;
				}

				transform.position = UserInfo.CameraPos;
				transform.rotation = Quaternion.Euler(UserInfo.CameraRotation);

				x = UserInfo.CameraRotation.y;
				y = UserInfo.CameraRotation.x;

				dontTouchInput = true;
			} else */
			if (screen == WindowPanels.PainSelector) {
				dontTouchInput = true;
				if (UserInfo.TargetBone != null) {
					target = UserInfo.TargetBone;

					transform.position = UserInfo.CameraPos;
					transform.rotation = Quaternion.Euler(UserInfo.CameraRotation);

					x = UserInfo.CameraRotation.y;
					y = UserInfo.CameraRotation.x;
				} else
					target = UserInfo.AvatarTempHip;
			} else {
				dontTouchInput = false;
				target = UserInfo.AvatarTempHip;

				if (CreationTab.pressedTab != CreationTab.TAB.NONE)
					OnClickAvatarEditTab(CreationTab.pressedTab);
			}
		} else {
			
			if (screen == WindowPanels.PainSelector) {
				UserInfo.TargetBone = target;
				UserInfo.CameraPos = transform.position;
				Vector3 rot = new Vector3();
				rot.y = x;
				rot.x = y;
				rot.z = transform.rotation.eulerAngles.z;
				UserInfo.CameraRotation = rot;

				UserInfo.SaveCameraPosition = new MiniJsonObject();
			}

			x = initRotation.y;
			y = initRotation.x;

			if (screen == WindowPanels.Session)
				transform.position = initPosition + new Vector3(0, offsetY, 0);
			else
				transform.position = initPosition;
			
			transform.rotation = initRotation;
			target = UserInfo.AvatarTempHip;
		}

		shouldCameraMove = move;
	}
		
	void LateUpdate() {
		if (target && shouldCameraMove && !LeanTouch.GuiInUse) {

			if (Input.GetMouseButtonUp(0)) {

				if (!tapping && tapOnModel) {
					tapOnModel = false;
					tapping = true;
					StopCoroutine("SingleTap");
					StartCoroutine("SingleTap");
				}
				if ((Time.time - LastTap) <= tapTime) {
					StopCoroutine("SingleTap");
					tapping = false;
					HitPoint point = OnTapPlayer(true, true);
					_isPositionChanging = true;
					if (point != null) {
						target = point.hitObject.transform;
					} else {
						OnResetCamera();
					}
				}
				LastTap = Time.time;
			} 

			if (Input.GetMouseButtonDown(0)) {
				if (OnTapPlayer(false, false) == null) {
					tapOnModel = false;
				} else
					tapOnModel = true;
			}

			List<LeanFinger> fingers = LeanTouch.GetFingers(true, 0);
			Vector3 worldDelta = LeanGesture.GetScaledDelta(fingers);

			if ((Mathf.Abs(worldDelta.x) > 0.1f || Mathf.Abs(worldDelta.y) > 0.1f) && !tapOnModel &&
			    fingers != null && fingers.Count == 1) {
				x += worldDelta.x * xSpeed * distance;
				y -= worldDelta.y * ySpeed;
				y = ClampAngle(y, yMinLimit, yMaxLimit);
			}

			if (fingers != null && fingers.Count > 1) {
				tapOnModel = false;
				tapping = false;
				StopCoroutine("SingleTap");
			}

			Quaternion targetRotation = Quaternion.Euler(y, x, 0);
			Vector3 negDistance = new Vector3(0.0f, 0, -distance);
			Vector3 position = targetRotation * negDistance + target.position;

			if (_isPositionChanging) {
				transform.position = Vector3.Lerp(transform.position, position, Time.deltaTime * 5);
				transform.rotation = Quaternion.Lerp(transform.rotation, targetRotation, Time.deltaTime * 5);
				if ((transform.position - position).sqrMagnitude <= 0.0001f)
					_isPositionChanging = false;
				return;
			}

			transform.rotation = targetRotation;
			transform.position = position;
		} else {
			tapOnModel = false;
			tapping = false;
		}
	}

	void OnResetCamera(){
		_isPositionChanging = true;
		if (ResetCamera != null)
			ResetCamera();
		x = initRotation.eulerAngles.y;
		y = initRotation.eulerAngles.x;
		target = UserInfo.AvatarTempHip;
	}

	IEnumerator SingleTap() {
		yield return new WaitForSeconds(tapTime);
		if (tapping) {
			tapping = false;

			HitPoint hit = OnTapPlayer(false, true);
			if (hit != null) {
				if (OnTouchModel != null)
					OnTouchModel(hit.hitPosition);
			}
		}
	}

	HitPoint OnTapPlayer(bool isDouble, bool print) {
		if (!dontTouchInput)
			return null;
		
		RaycastHit hit;
		var p = Camera.main.ScreenPointToRay(Input.mousePosition);
		if (Physics.Raycast(p, out hit, 100)) {
			
			if (print)
				Debug.Log(string.Format("tag {0} and name {1} and type {2}", hit.collider.gameObject.tag, hit.collider.gameObject.name, isDouble));

			if (isDouble) {
				if (CheckBoneNames(hit.collider.gameObject.name)) {
					HitPoint obj = new HitPoint(hit.point, hit.collider.gameObject);
					return obj;
				} 
			} else {
				if (hit.collider.gameObject.tag == "Player" || CheckBoneNames(hit.collider.gameObject.name)) {
					HitPoint obj = new HitPoint(hit.point, hit.collider.gameObject);
					return obj;
				} 
			}

		}
		return null;
	}

	bool CheckBoneNames(string name) {
		for (int i = 0; i < UserInfo.AvatarBones.Length; i++) {
			if (UserInfo.AvatarBones[i].name == name)
				return true;
		}
		return false;
	}

	public static float ClampAngle(float angle, float min, float max) {
		if (angle < -360F)
			angle += 360F;
		if (angle > 360F)
			angle -= 360F;
		return Mathf.Clamp(angle, min, max);
	}
}

class HitPoint {
	public Vector3 hitPosition;
	public GameObject hitObject;

	public HitPoint(Vector3 pos, GameObject name) {
		hitPosition = pos;
		hitObject = name;
	}
}