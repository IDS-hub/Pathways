using UnityEngine;

namespace Lean.Touch {
	// This modifies LeanCameraZoom to be smooth
	public class LeanCameraZoomSmooth : LeanCameraZoom {
		[Tooltip("How quickly the zoom reaches the target value")]
		public float Dampening = 10.0f;

		private float currentZoom;
		bool shouldCameraMove = false;

		float initZoom;

		protected virtual void OnEnable() {
			currentZoom = Zoom;
			initZoom = currentZoom;
			PainSelector.OnPainSelector += ShouldCameraMove;
			CreationTab.OnClick += OnClickAvatarEditTab;
			MouseOrbitImproved.ResetCamera += ResetZoom;
		}

		void OnDisable() {
			PainSelector.OnPainSelector -= ShouldCameraMove;
			CreationTab.OnClick -= OnClickAvatarEditTab;
			MouseOrbitImproved.ResetCamera -= ResetZoom;
		}

		// camera only moves on specific screens
		void ShouldCameraMove(bool move, WindowPanels screen) {
			shouldCameraMove = move;
			if (move) {
				/*if (UserInfo.TargetBone != null && UserInfo.UserOriginalDiagnosis != null && UserInfo.UserOriginalDiagnosis.Count == 1 && screen == WindowPanels.PainSelector) {
					currentZoom = UserInfo.CameraFov;
					Zoom = UserInfo.CameraFov;
					SetZoom(currentZoom);
				} else */
				if (screen == WindowPanels.PainSelector) {
					if (UserInfo.TargetBone != null) {
						currentZoom = UserInfo.CameraFov;
						Zoom = UserInfo.CameraFov;
						SetZoom(currentZoom);
					}
				} else {
					currentZoom = initZoom;
					Zoom = initZoom;
					SetZoom(currentZoom);
				}

				if (CreationTab.pressedTab != CreationTab.TAB.NONE)
					OnClickAvatarEditTab(CreationTab.pressedTab);

			} else {
				if (screen == WindowPanels.PainSelector) {
					UserInfo.CameraFov = currentZoom;
					UserInfo.SaveCameraPosition = new MiniJsonObject();
					Debug.Log("saving FOV");
				}

				currentZoom = initZoom;
				Zoom = initZoom;
				SetZoom(currentZoom);
			}
		}

		void ResetZoom() {
			Zoom = initZoom;
		}

		// on tab click from Avatar Customisation, camera needs to zoom
		void OnClickAvatarEditTab(CreationTab.TAB tab) {
			Debug.Log("OnClickAvatarEditTab " + tab.ToString());
			if (tab == CreationTab.TAB.Eyes || tab == CreationTab.TAB.Hairstyle || tab == CreationTab.TAB.Face || tab == CreationTab.TAB.Lips || tab == CreationTab.TAB.Nose) {
				//currentZoom = 25f;
				Zoom = 25f;
				//SetZoom(currentZoom);

			} else {
				//currentZoom = initZoom;
				Zoom = initZoom;
				//SetZoom(currentZoom);
			}
		}

		protected override void LateUpdate() {
			if (!shouldCameraMove)
				return;

			base.LateUpdate();

			var factor = LeanTouch.GetDampenFactor(Dampening, Time.deltaTime);
			currentZoom = Mathf.Lerp(currentZoom, Zoom, factor);
			SetZoom(currentZoom);

		}
	}
}